<?php

use App\Http\Controllers\Mailbox\MailboxController;
use App\Models\User;
use App\Services\Cpanel\CpanelTransport;
use App\Services\Cpanel\HttpTransport;
use App\Services\Cpanel\UapiTransport;
use App\Services\CpanelMail;
use App\Services\CpanelMailException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\LogicException;

uses(RefreshDatabase::class);

const T_DOMAIN = 'fokusteguhdinamik.com.my';

/**
 * UapiTransport with the process replaced: records each argv array and
 * answers with a canned exit code, stdout and stderr (or throws).
 */
class RecordingUapi extends UapiTransport
{
    public array $commands = [];

    public function __construct(
        public int $exit = 0,
        public string $stdout = '{"apiversion":3,"func":"x","module":"Email","result":{"status":1,"errors":null,"data":[]}}',
        public string $stderr = '',
        public ?Throwable $throw = null,
        public ?string $failOnly = null,
    ) {
        parent::__construct('/usr/local/cpanel/bin/uapi');
    }

    protected function execute(array $command): array
    {
        $this->commands[] = $command;
        if ($this->throw) {
            throw $this->throw;
        }
        if ($this->failOnly !== null && $command[3] !== $this->failOnly) {
            return [0, '{"result":{"status":1,"errors":null,"data":[]}}', ''];
        }

        return [$this->exit, $this->stdout, $this->stderr];
    }
}

function uapiMail(RecordingUapi $t): CpanelMail
{
    app()->instance(CpanelTransport::class, $t);

    return app(CpanelMail::class);
}

test('uapi is the default transport, http when configured', function () {
    config(['services.cpanel.transport' => 'uapi']);
    expect(app(CpanelTransport::class))->toBeInstanceOf(UapiTransport::class);
    config(['services.cpanel.transport' => 'http']);
    expect(app(CpanelTransport::class))->toBeInstanceOf(HttpTransport::class);
});

test('uapi add builds the exact argv array', function () {
    $t = new RecordingUapi;
    uapiMail($t)->add(T_DOMAIN, 'sales', 'p@ss w0rd;$(rm -rf ~)');

    expect($t->commands)->toBe([[
        '/usr/local/cpanel/bin/uapi', '--output=json', 'Email', 'add_pop',
        'email=sales', 'domain='.T_DOMAIN, 'password=p%40ss%20w0rd%3B%24%28rm%20-rf%20~%29',
        'quota=0', 'send_welcome_email=0',
    ]]);
});

test('uapi passwd builds the exact argv array', function () {
    $t = new RecordingUapi;
    uapiMail($t)->setPassword(T_DOMAIN, 'sales', 'newpassword9');

    expect($t->commands)->toBe([[
        '/usr/local/cpanel/bin/uapi', '--output=json', 'Email', 'passwd_pop',
        'email=sales', 'domain='.T_DOMAIN, 'password=newpassword9',
    ]]);
});

test('uapi list builds the exact argv array and reads result.data', function () {
    $t = new RecordingUapi(stdout: json_encode(['apiversion' => 3, 'module' => 'Email', 'func' => 'list_pops_with_disk', 'result' => [
        'status' => 1, 'errors' => null, 'data' => [['email' => 'hello@'.T_DOMAIN, 'domain' => T_DOMAIN], ['email' => 'x@other.com', 'domain' => 'other.com']],
    ]]));
    $rows = uapiMail($t)->list(T_DOMAIN);

    expect($t->commands)->toBe([['/usr/local/cpanel/bin/uapi', '--output=json', 'Email', 'list_pops_with_disk', 'domain='.T_DOMAIN]]);
    expect(array_column($rows, 'email'))->toBe(['hello@'.T_DOMAIN]);
});

test('uapi result.status 0 raises the first result.errors item', function () {
    $t = new RecordingUapi(stdout: '{"result":{"status":0,"errors":["The account sales@x already exists!"],"data":null}}');
    expect(fn () => uapiMail($t)->add(T_DOMAIN, 'sales', 'longenough1'))
        ->toThrow(CpanelMailException::class, 'The account sales@x already exists!');
});

test('a user outside the regex never reaches argv', function () {
    $t = new RecordingUapi;
    expect(fn () => uapiMail($t)->add(T_DOMAIN, 'a b --user=root', 'longenough1'))->toThrow(InvalidArgumentException::class);
    expect($t->commands)->toBe([]);
});

test('uapi non-zero exit gives the friendly message and one log line without the password', function () {
    $t = new RecordingUapi(exit: 1, stdout: 'not json', stderr: str_repeat('boom password=secretpass99 ', 30), failOnly: 'add_pop');
    app()->instance(CpanelTransport::class, $t);
    Log::spy();

    $this->actingAs(User::factory()->create(['mail_domain' => T_DOMAIN]))->from('/mailbox')->post('/mailbox/add', [
        'username' => 'sales', 'password' => 'secretpass99', 'password_confirmation' => 'secretpass99',
    ])->assertRedirect('/mailbox')->assertSessionHas('error', MailboxController::UNAVAILABLE);

    Log::shouldHaveReceived('warning')->once()->withArgs(function ($msg, $ctx) {
        $all = json_encode([$msg, $ctx]);

        return $ctx['error'] === 'uapi failed'
            && strlen($ctx['detail']) <= 200 && str_contains($ctx['detail'], 'boom')
            && ! str_contains($all, 'secretpass99');
    });
});

test('uapi non-JSON stdout with exit 0 is a uapi failure', function () {
    $t = new RecordingUapi(stdout: 'Setting locale failed');
    expect(fn () => uapiMail($t)->list(T_DOMAIN))->toThrow(CpanelMailException::class, 'uapi failed');
});

test('proc_open disabled is reported as process disabled', function () {
    $t = new RecordingUapi(throw: new LogicException('The Process class relies on proc_open, which is not available on your PHP installation.'));
    expect(fn () => uapiMail($t)->list(T_DOMAIN))->toThrow(CpanelMailException::class, 'process disabled');
});

test('http transport still builds the old token request', function () {
    config(['services.cpanel.transport' => 'http', 'services.cpanel.host' => 'cpanel.test:2083', 'services.cpanel.user' => 'acct', 'services.cpanel.token' => 'TESTTOKEN']);
    Http::fake(['*' => Http::response(['status' => 1, 'errors' => null, 'data' => null])]);

    app(CpanelMail::class)->add(T_DOMAIN, 'sales', 'longenough1');

    Http::assertSent(function (Request $r) {
        $d = $r->data();
        ksort($d);

        return $r->method() === 'POST'
            && $r->url() === 'https://cpanel.test:2083/execute/Email/add_pop'
            && $r->header('Authorization')[0] === 'cpanel acct:TESTTOKEN'
            && $d == ['domain' => T_DOMAIN, 'email' => 'sales', 'password' => 'longenough1', 'quota' => 0, 'send_welcome_email' => 0];
    });
});

test('mailbox:doctor prints transport, status and count, never a password', function () {
    app()->instance(CpanelTransport::class, new RecordingUapi(stdout: json_encode(['result' => [
        'status' => 1, 'errors' => null, 'data' => [['email' => 'hello@imotion.my', 'domain' => 'imotion.my']],
    ]])));

    $this->artisan('mailbox:doctor', ['--domain' => 'imotion.my'])
        ->expectsOutput('Transport: uapi')
        ->expectsOutput('Status: ok')
        ->expectsOutput('Mailboxes on imotion.my: 1')
        ->assertSuccessful();
});

test('mailbox:doctor fails cleanly when uapi fails', function () {
    app()->instance(CpanelTransport::class, new RecordingUapi(throw: new LogicException('proc_open')));

    $this->artisan('mailbox:doctor', ['--domain' => 'imotion.my'])
        ->expectsOutput('Transport: uapi')
        ->expectsOutputToContain('process disabled')
        ->assertFailed();
});
