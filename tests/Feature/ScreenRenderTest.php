<?php

namespace Tests\Feature;

use App\Http\Controllers\Mailbox\MailboxController;
use App\Models\User;
use App\Services\Cpanel\CpanelTransport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Fakes\FakeCpanelTransport;
use Tests\TestCase;

uses(RefreshDatabase::class);

const SHOT_DIR = 'D:/ClaudeProjects/iMotion Production/Website/repo/imotion/storage/screens/';

/**
 * Not a test of behaviour: renders the real Blade screens against a SQLite file
 * (this test's own connection) so the screenshots in the job report come from the
 * shipped views. Run with SCREENS=1; skipped otherwise.
 */
function shot(string $name, string $html): void
{
    if (getenv('SCREENS') !== '1') {
        return;
    }
    @mkdir(SHOT_DIR, 0777, true);
    file_put_contents(SHOT_DIR.$name.'.html', $html);
}

function shotRows(): array
{
    $r = fn (string $u, string $used, $bytes, string $quota = 'None') => [
        'email' => $u, 'login' => $u, 'user' => explode('@', $u)[0], 'domain' => explode('@', $u)[1],
        'humandiskused' => $used, 'humandiskquota' => $quota, '_diskused' => $bytes,
    ];

    return [
        $r('admin@fokusteguhdinamik.com.my', '412.5 MB', 432537600, 'Unlimited'),
        $r('sales@fokusteguhdinamik.com.my', '1.2 GB', 1288490189, '2 GB'),
        $r('hr@fokusteguhdinamik.com.my', '86 MB', 90177536, 'Unlimited'),
    ];
}

function shotUser(string $domain, string $name, ?string $host): User
{
    return User::factory()->create(['name' => $name, 'mail_domain' => $domain, 'portal_host' => $host]);
}

beforeEach(function () {
    config(['app.url' => 'https://imotion.my', 'services.cpanel.max_mailboxes' => 20]);
});

test('screens: client-host login, client dashboard, imotion.my dashboard', function () {
    if (getenv('SCREENS') !== '1') {
        $this->markTestSkipped('set SCREENS=1 to render the screens');
    }
    Mail::fake();
    $t = new FakeCpanelTransport(shotRows());
    app()->instance(CpanelTransport::class, $t);

    shotUser('fokusteguhdinamik.com.my', 'Fokus Teguh Dinamik', 'mailbox.fokusteguhdinamik.com.my');
    $ftd = User::where('mail_domain', 'fokusteguhdinamik.com.my')->firstOrFail();

    shot('client-login', $this->get('https://mailbox.fokusteguhdinamik.com.my/mailbox/login')->assertOk()->getContent());
    shot('client-dashboard', $this->actingAs($ftd)->get('https://mailbox.fokusteguhdinamik.com.my/mailbox')->assertOk()->getContent());

    $imotion = shotUser('imotion.my', 'iMotion Production', null);
    shot('imotion-dashboard', $this->actingAs($imotion)->get('https://imotion.my/mailbox')->assertOk()->getContent());
    auth()->logout();
    shot('imotion-login', $this->get('https://imotion.my/mailbox/login')->assertOk()->getContent());
});

test('no iMotion wording on a client host', function () {
    Mail::fake();
    app()->instance(CpanelTransport::class, new FakeCpanelTransport(shotRows()));
    shotUser('fokusteguhdinamik.com.my', 'Fokus Teguh Dinamik', 'mailbox.fokusteguhdinamik.com.my');
    $ftd = User::where('mail_domain', 'fokusteguhdinamik.com.my')->firstOrFail();
    $host = 'https://mailbox.fokusteguhdinamik.com.my';

    $pages = [
        'login' => $this->get($host.'/mailbox/login')->assertOk()->getContent(),
        'set-password' => $this->get($host.'/mailbox/reset-password/tok123?email=owner@fokusteguhdinamik.com.my')->getContent(),
        'dashboard' => $this->actingAs($ftd)->get($host.'/mailbox')->assertOk()->getContent(),
        'dashboard-unavailable' => $this->actingAs($ftd)->get('/mailbox')->getContent(),
    ];

    foreach (['login', 'set-password', 'dashboard'] as $page) {
        expect(preg_match('/imotion\.my/i', $pages[$page]))->toBe(0, $page.' contains imotion.my');
        expect(preg_match('/iMotion/i', $pages[$page]))->toBe(0, $page.' contains iMotion');
        expect(preg_match('/(src|href|action)="[^"]*imotion/i', $pages[$page]))->toBe(0, $page.' links to an imotion host');
    }
    expect($pages['dashboard'])->toContain('<title>Fokus Teguh Dinamik · Mailboxes</title>')
        ->and($pages['login'])->toContain('Forgot your password? Contact your administrator.');
});

test('no iMotion wording on a client host: cap, reserved name and unavailable', function () {
    Mail::fake();
    $rows = [];
    for ($i = 4; $i <= 23; $i++) {
        $rows[] = ['email' => "u{$i}@fokusteguhdinamik.com.my", 'humandiskused' => '1 MB', 'humandiskquota' => 'None', '_diskused' => 1048576];
    }
    app()->instance(CpanelTransport::class, new FakeCpanelTransport($rows));
    $ftd = shotUser('fokusteguhdinamik.com.my', 'Fokus Teguh Dinamik', 'mailbox.fokusteguhdinamik.com.my');
    $host = 'https://mailbox.fokusteguhdinamik.com.my';

    expect($this->actingAs($ftd)->get($host.'/mailbox')->getContent())
        ->toContain('To add more mailboxes, contact your administrator.');

    $this->actingAs($ftd)->from($host.'/mailbox')->post($host.'/mailbox/add', [
            'username' => 'admin', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
        ])->assertSessionHasErrors(['username' => 'This name is reserved.']);

    app()->instance(CpanelTransport::class, new FakeCpanelTransport($rows));
    $reserved = $this->actingAs($ftd)->get($host.'/mailbox')->getContent();
    expect(preg_match('/iMotion/i', $reserved))->toBe(0);
});

test('no iMotion wording on a client host when the mailbox service is unavailable', function () {
    Mail::fake();
    app()->instance(CpanelTransport::class, new FakeCpanelTransport([], ['status' => 0, 'errors' => ['boom'], 'data' => null]));
    $ftd = shotUser('fokusteguhdinamik.com.my', 'Fokus Teguh Dinamik', 'mailbox.fokusteguhdinamik.com.my');
    $host = 'https://mailbox.fokusteguhdinamik.com.my';

    $this->actingAs($ftd)->from($host.'/mailbox')->post($host.'/mailbox/add', [
        'username' => 'newperson', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertRedirect()->assertSessionHas('error', MailboxController::UNAVAILABLE_CLIENT);

    expect(MailboxController::UNAVAILABLE_CLIENT)->not->toMatch('/iMotion/i');
});
