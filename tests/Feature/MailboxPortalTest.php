<?php

use App\Models\User;
use App\Services\Cpanel\CpanelTransport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\Fakes\FakeCpanelTransport;

uses(RefreshDatabase::class);

const DOMAIN = 'fokusteguhdinamik.com.my';

beforeEach(function () {
    config(['services.cpanel.max_mailboxes' => 20]);
});

function client(): User
{
    return User::factory()->create(['mail_domain' => DOMAIN]);
}

function row(string $email): array
{
    [$u, $d] = explode('@', $email);

    return ['email' => $email, 'login' => $email, 'user' => $u, 'domain' => $d, 'humandiskused' => '1 MB', 'humandiskquota' => 'None'];
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
    $this->get('/mailbox/login')->assertOk()->assertSee('Forgot your password? Contact iMotion.');
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
    ])->assertRedirect('/mailbox')->assertSessionHas('error', 'Mailbox service is unavailable right now. iMotion has been notified.');

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

test('every mailbox row and the add flash link to the user domain webmail only', function () {
    fakeCpanel([row('sales@'.DOMAIN), row('boss@other-client.com')]);
    $user = client();

    $html = $this->actingAs($user)->get('/mailbox')->assertOk()->getContent();
    expect($html)->toContain('href="https://'.DOMAIN.'/webmail" target="_blank" rel="noopener">Open webmail</a>');
    preg_match_all('#https://([^/"\s]+)/webmail#', $html, $m);
    expect(array_values(array_unique($m[1])))->toBe([DOMAIN]);

    $this->actingAs($user)->post('/mailbox/add', [
        'username' => 'info', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertRedirect('/mailbox');
    $this->actingAs($user)->get('/mailbox')
        ->assertSee('Done: info@'.DOMAIN, false)
        ->assertSee('<a href="https://'.DOMAIN.'/webmail" target="_blank" rel="noopener">Open webmail</a>', false);
});
