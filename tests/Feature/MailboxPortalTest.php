<?php

use App\Http\Controllers\Mailbox\MailboxController;
use App\Models\User;
use App\Services\Cpanel\CpanelTransport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\Fakes\FakeCpanelTransport;

uses(RefreshDatabase::class);

const DOMAIN = 'fokusteguhdinamik.com.my';

beforeEach(function () {
    config(['services.cpanel.max_mailboxes' => 20, 'app.url' => 'https://imotion.my']);
});

function client(): User
{
    return User::factory()->create(['mail_domain' => DOMAIN]);
}

/** An imotion.my mailbox user: the home host, where the iMotion wording is kept. */
function home(): User
{
    return User::factory()->create(['mail_domain' => 'imotion.my']);
}

function row(string $email): array
{
    [$u, $d] = explode('@', $email);

    return ['email' => $email, 'login' => $email, 'user' => $u, 'domain' => $d, 'humandiskused' => '1 MB', 'humandiskquota' => 'None', '_diskused' => 1048576];
}

/** Binds a fake CpanelTransport in the container; nothing reaches uapi or HTTP. */
function fakeCpanel(array $rows = [], array $writeReply = ['status' => 1, 'errors' => null, 'data' => null]): FakeCpanelTransport
{
    $fake = new FakeCpanelTransport($rows, $writeReply);
    app()->instance(CpanelTransport::class, $fake);

    return $fake;
}

test('guest is redirected to login and cannot add', function () {
    $fake = fakeCpanel();
    $this->get('/mailbox')->assertRedirect('/mailbox/login');
    $this->post('/mailbox/add', ['username' => 'x'])->assertRedirect('/mailbox/login');
    expect($fake->calls)->toBe([]);
});

test('login page shows the contact line', function () {
    $this->get('/mailbox/login')->assertOk()->assertSee('Forgot your password? Contact your administrator.');
});

test('user without mail_domain gets 403', function () {
    $this->actingAs(User::factory()->create(['mail_domain' => null]))->get('/mailbox')->assertForbidden();
});

test('dashboard lists only the user domain', function () {
    fakeCpanel([row('sales@'.DOMAIN), row('boss@other-client.com')]);

    $this->actingAs(client())->get('/mailbox')
        ->assertOk()
        ->assertSee('Mailboxes for '.DOMAIN)
        ->assertSee('sales@'.DOMAIN)
        ->assertDontSee('other-client.com');
});

test('add sends exactly the five fields with the user domain, ignoring a request domain', function () {
    $fake = fakeCpanel();

    $this->actingAs(client())->post('/mailbox/add', [
        'username' => 'sales', 'domain' => 'evil.com',
        'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertRedirect('/mailbox');

    $sent = $fake->sent('add_pop');
    expect($sent)->toHaveCount(1);
    $d = $sent[0];
    ksort($d);
    expect($d)->toBe(['domain' => DOMAIN, 'email' => 'sales', 'password' => 'longenough1', 'quota' => 0, 'send_welcome_email' => 0]);
    expect($fake->calls[1]['module'])->toBe('Email')->and($fake->calls[1]['write'])->toBeTrue();
});

test('username is lower-cased', function () {
    $fake = fakeCpanel();
    $this->actingAs(client())->post('/mailbox/add', [
        'username' => 'Sales', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertSessionHasNoErrors();
    expect($fake->sent('add_pop')[0]['email'])->toBe('sales');
});

test('bad usernames are refused', function (string $name) {
    $fake = fakeCpanel();
    $this->actingAs(client())->post('/mailbox/add', [
        'username' => $name, 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertSessionHasErrors('username');
    expect($fake->sent('add_pop'))->toBe([]);
})->with(['..x', 'a b', 'cpanel', str_repeat('a', 65)]);

test('reset password sends email, domain, password to passwd_pop', function () {
    $fake = fakeCpanel([row('sales@'.DOMAIN)]);
    $this->actingAs(client())->post('/mailbox/sales@'.DOMAIN.'/password', [
        'password' => 'newpassword9', 'password_confirmation' => 'newpassword9',
    ])->assertRedirect('/mailbox');

    $sent = $fake->sent('passwd_pop');
    expect($sent)->toHaveCount(1);
    $d = $sent[0];
    ksort($d);
    expect($d)->toBe(['domain' => DOMAIN, 'email' => 'sales', 'password' => 'newpassword9']);
});

test('reset on another domain address is refused', function () {
    $fake = fakeCpanel();
    $this->actingAs(client())->post('/mailbox/boss@other-client.com/password', [
        'password' => 'newpassword9', 'password_confirmation' => 'newpassword9',
    ])->assertNotFound();
    expect($fake->calls)->toBe([]);
});

test('status 0 shows the friendly message and logs once without the password', function () {
    fakeCpanel([], ['status' => 0, 'errors' => ['Internal secret detail'], 'data' => null]);
    Log::spy();

    $this->actingAs(client())->from('/mailbox')->post('/mailbox/add', [
        'username' => 'sales', 'password' => 'secretpass99', 'password_confirmation' => 'secretpass99',
    ])->assertRedirect('/mailbox')->assertSessionHas('error', MailboxController::unavailable());

    Log::shouldHaveReceived('warning')->once()->withArgs(function ($msg, $ctx) {
        return ! str_contains(json_encode([$msg, $ctx]), 'secretpass99');
    });

    $this->get('/mailbox')->assertDontSee('Internal secret detail');
});

test('duplicate address shows the friendly duplicate message', function () {
    fakeCpanel([], ['status' => 0, 'errors' => ['The account sales@x already exists!'], 'data' => null]);
    $this->actingAs(client())->post('/mailbox/add', [
        'username' => 'sales', 'password' => 'secretpass99', 'password_confirmation' => 'secretpass99',
    ])->assertSessionHasErrors(['username' => 'This address already exists.']);
});

test('cap of 20: form hidden and add returns 422', function () {
    $rows = array_map(fn ($i) => row("u{$i}@".DOMAIN), range(1, 20));
    $fake = fakeCpanel($rows);
    $user = client();

    $this->actingAs($user)->get('/mailbox')->assertOk()->assertDontSee('Add mailbox');
    $this->actingAs($user)->post('/mailbox/add', [
        'username' => 'extra', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertStatus(422);
    expect($fake->sent('add_pop'))->toBe([]);
});

test('mailbox:client prints a reset url and no password', function () {
    $this->artisan('mailbox:client', ['email' => 'owner@'.DOMAIN, 'domain' => DOMAIN, '--name' => 'Fokus'])
        ->expectsOutputToContain('/mailbox/reset-password/')
        ->assertSuccessful();
    expect(User::where('email', 'owner@'.DOMAIN)->value('mail_domain'))->toBe(DOMAIN);
});

test('every mailbox row and the add flash post to the webmail route for that address', function () {
    fakeCpanel([row('sales@'.DOMAIN)]);
    $user = client();

    $html = $this->actingAs($user)->get('/mailbox')->assertOk()->getContent();
    expect($html)->toContain('<form class="webmail" method="POST" action="'.url('/mailbox/sales@'.DOMAIN.'/webmail').'" target="_blank" rel="noopener">')
        ->and($html)->toContain('Open webmail</button>');

    $this->actingAs($user)->post('/mailbox/add', [
        'username' => 'info', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertRedirect('/mailbox');
    $this->actingAs($user)->get('/mailbox')
        ->assertSee('Done: info@'.DOMAIN, false)
        ->assertSee('action="'.url('/mailbox/info@'.DOMAIN.'/webmail').'" target="_blank" rel="noopener">', false);
});

test('webmail route sends exactly login, domain, remote_address for the user domain', function () {
    $fake = fakeCpanel([], ['status' => 1, 'errors' => null, 'data' => [
        'hostname' => 'lux.ix-dns.com', 'token' => '/cpsess1234567890', 'session' => 'sales@x:AbC123:CREATE_WEBMAIL_SESSION_FOR_MAIL_USER,deadbeef',
    ]]);

    $this->actingAs(client())->post('/mailbox/Sales@'.DOMAIN.'/webmail', ['domain' => 'evil.com', 'login' => 'boss'], ['REMOTE_ADDR' => '203.0.113.7'])
        ->assertOk();

    $sent = $fake->sent('create_webmail_session_for_mail_user');
    expect($sent)->toHaveCount(1);
    $d = $sent[0];
    ksort($d);
    expect($d)->toBe(['domain' => DOMAIN, 'login' => 'sales', 'remote_address' => '203.0.113.7'])
        ->and($fake->calls[0]['module'])->toBe('Session');
});

test('webmail for another domain address is 404 and calls nothing', function () {
    $fake = fakeCpanel();
    $this->post('/mailbox/sales@'.DOMAIN.'/webmail')->assertRedirect('/mailbox/login');
    $this->actingAs(client())->post('/mailbox/boss@other-client.com/webmail')->assertNotFound();
    $this->actingAs(client())->post('/mailbox/bad%20user@'.DOMAIN.'/webmail')->assertNotFound();
    expect($fake->calls)->toBe([]);
});

test('webmail redirect page posts only the session to hostname:2096 token /login', function () {
    fakeCpanel([], ['status' => 1, 'errors' => null, 'data' => [
        'hostname' => 'lux.ix-dns.com', 'token' => '/cpsess1234567890', 'session' => 'sales@x:AbC123:CREATE,deadbeef',
    ]]);

    $res = $this->actingAs(client())->post('/mailbox/sales@'.DOMAIN.'/webmail')->assertOk();
    $html = $res->getContent();

    expect($html)->toContain('action="https://lux.ix-dns.com:2096/cpsess1234567890/login"')
        ->and($html)->toContain('method="POST"')
        ->and($html)->toContain('<input type="hidden" name="session" value="sales@x:AbC123:CREATE,deadbeef">')
        ->and($html)->toContain('Opening webmail…')
        ->and($html)->toContain('Continue</button>');
    preg_match_all('#<input\b[^>]*>#', $html, $inputs);
    expect($inputs[0])->toHaveCount(1);
    expect($html)->not->toContain('_token')->and($html)->not->toContain(DOMAIN);
    expect($res->headers->get('Cache-Control'))->toContain('no-store');
});

test('webmail falls back to the configured host when cPanel returns no hostname', function () {
    config(['services.cpanel.webmail_host' => 'lux.ix-dns.com']);
    fakeCpanel([], ['status' => 1, 'errors' => null, 'data' => [
        'hostname' => null, 'token' => '/cpsess42', 'session' => 'abc',
    ]]);

    $this->actingAs(client())->post('/mailbox/sales@'.DOMAIN.'/webmail')
        ->assertOk()->assertSee('action="https://lux.ix-dns.com:2096/cpsess42/login"', false);
});

test('webmail failure shows the friendly message and logs one line without the session', function () {
    fakeCpanel([], ['status' => 0, 'errors' => ['Internal secret detail'], 'data' => null]);
    Log::spy();

    $this->actingAs(client())->post('/mailbox/sales@'.DOMAIN.'/webmail')
        ->assertRedirect('/mailbox')
        ->assertSessionHas('error', MailboxController::unavailable());

    Log::shouldHaveReceived('warning')->once()->withArgs(function ($msg, $ctx) {
        return $ctx['function'] === 'Session/create_webmail_session_for_mail_user'
            && $ctx['mailbox'] === 'sales@'.DOMAIN;
    });
});

test('a malformed session reply is treated as a failure, never rendered', function () {
    fakeCpanel([], ['status' => 1, 'errors' => null, 'data' => [
        'hostname' => 'evil.example"><script>', 'token' => '/cpsess1', 'session' => 'abc',
    ]]);
    Log::spy();

    $this->actingAs(client())->post('/mailbox/sales@'.DOMAIN.'/webmail')->assertRedirect('/mailbox');
    Log::shouldHaveReceived('warning')->once()->withArgs(fn ($msg, $ctx) => ! str_contains(json_encode($ctx), 'abc'));
});

test('unlimited quota shows Unlimited and no disk use shows 0 B', function () {
    fakeCpanel([
        ['email' => 'a@'.DOMAIN, 'humandiskused' => 'None', 'humandiskquota' => 'None'],
        ['email' => 'b@'.DOMAIN, 'diskused' => null, 'diskquota' => null],
        ['email' => 'c@'.DOMAIN, 'humandiskused' => '1.2 MB', 'humandiskquota' => '1 GB'],
    ]);

    $html = $this->actingAs(client())->get('/mailbox')->assertOk()->getContent();
    expect(preg_match_all('/<td class="q" data-label="Quota"><span class="pill ok">.*?Unlimited<\/span><\/td>/', $html))->toBe(2)
        ->and(substr_count($html, '<td class="used" data-label="Disk used">0 B</td>'))->toBe(2)
        ->and($html)->toContain('<td class="used" data-label="Disk used">1.2 MB</td>')
        ->and($html)->toContain('<span class="pill neutral">1 GB</span>')
        ->and($html)->not->toContain('None');
});

test('GET reset-password without a token redirects to login with the ask-administrator status', function () {
    $this->get('/mailbox/reset-password')
        ->assertRedirect('/mailbox/login')
        ->assertSessionHas('status', 'Contact your administrator for a new set-password link.');
    $this->get('/mailbox/login')->assertSee('Contact your administrator for a new set-password link.');
});

test('reset with an invalid token shows the expired-link sentence', function () {
    User::factory()->create(['email' => 'owner@'.DOMAIN, 'mail_domain' => DOMAIN]);

    $this->from('/mailbox/reset-password/bad')->post('/mailbox/reset-password', [
        'token' => 'not-a-real-token', 'email' => 'owner@'.DOMAIN,
        'password' => 'newpassword9', 'password_confirmation' => 'newpassword9',
    ])->assertSessionHasErrors(['email' => 'This link has expired or was replaced. Contact your administrator for a new one.']);
});

test('reset for an unknown user shows the expired-link sentence', function () {
    $this->from('/mailbox/reset-password/bad')->post('/mailbox/reset-password', [
        'token' => 'whatever', 'email' => 'nobody@'.DOMAIN,
        'password' => 'newpassword9', 'password_confirmation' => 'newpassword9',
    ])->assertSessionHasErrors(['email' => 'This link has expired or was replaced. Contact your administrator for a new one.']);
});

test('delete a mailbox of the user domain sends email, domain, flush to delete_pop', function () {
    $fake = fakeCpanel([row('sales@'.DOMAIN)]);

    $this->actingAs(client())->post('/mailbox/Sales@'.DOMAIN.'/delete')
        ->assertRedirect('/mailbox')
        ->assertSessionHas('deleted', 'sales@'.DOMAIN);

    $sent = $fake->sent('delete_pop');
    expect($sent)->toHaveCount(1);
    $d = $sent[0];
    ksort($d);
    expect($d)->toBe(['domain' => DOMAIN, 'email' => 'sales', 'flush' => 1]);
    expect($fake->calls[0]['module'])->toBe('Email')->and($fake->calls[0]['write'])->toBeTrue();
});

test('delete on another domain address is 404 and calls nothing', function () {
    $fake = fakeCpanel();

    $this->actingAs(client())->post('/mailbox/boss@other-client.com/delete')->assertNotFound();

    expect($fake->sent('delete_pop'))->toBe([]);
});

test('delete of an invalid local part is 404 and calls nothing', function () {
    $fake = fakeCpanel();

    // -bad@… is a valid route segment; the local part is what the controller refuses.
    $this->actingAs(client())->post('/mailbox/-bad@'.DOMAIN.'/delete')->assertNotFound();

    expect($fake->sent('delete_pop'))->toBe([]);
});

test('a guest cannot delete a mailbox', function () {
    $fake = fakeCpanel();

    $this->post('/mailbox/sales@'.DOMAIN.'/delete')->assertRedirect('/mailbox/login');

    expect($fake->sent('delete_pop'))->toBe([]);
});

test('a failed delete goes back with the friendly message and no deleted key', function () {
    fakeCpanel([], ['status' => 0, 'errors' => ['Internal secret detail'], 'data' => null]);
    Log::spy();

    $this->actingAs(client())->from('/mailbox')->post('/mailbox/sales@'.DOMAIN.'/delete')
        ->assertRedirect('/mailbox')
        ->assertSessionHas('error', MailboxController::unavailable())
        ->assertSessionMissing('deleted');

    Log::shouldHaveReceived('warning')->once()->withArgs(function ($msg, $ctx) {
        return $ctx['function'] === 'Email/delete_pop' && $ctx['mailbox'] === 'sales@'.DOMAIN;
    });
});
