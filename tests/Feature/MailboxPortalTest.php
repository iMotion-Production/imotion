<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

const DOMAIN = 'fokusteguhdinamik.com.my';

beforeEach(function () {
    config([
        'services.cpanel.host' => 'cpanel.test:2083',
        'services.cpanel.user' => 'acct',
        'services.cpanel.token' => 'TESTTOKEN',
        'services.cpanel.max_mailboxes' => 20,
    ]);
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

function fakeCpanel(array $rows = [], array $writeReply = ['status' => 1, 'errors' => null, 'data' => null]): void
{
    Http::fake([
        '*/execute/Email/list_pops_with_disk*' => Http::response(['status' => 1, 'errors' => null, 'data' => $rows]),
        '*/execute/Email/add_pop*' => Http::response($writeReply),
        '*/execute/Email/passwd_pop*' => Http::response($writeReply),
    ]);
}

test('guest is redirected to login and cannot add', function () {
    Http::fake();
    $this->get('/mailbox')->assertRedirect('/mailbox/login');
    $this->post('/mailbox/add', ['username' => 'x'])->assertRedirect('/mailbox/login');
    Http::assertNothingSent();
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
    fakeCpanel();

    $this->actingAs(client())->post('/mailbox/add', [
        'username' => 'sales', 'domain' => 'evil.com',
        'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertRedirect('/mailbox');

    Http::assertSent(function (Request $r) {
        if (! str_contains($r->url(), 'Email/add_pop')) {
            return false;
        }
        $d = $r->data();
        ksort($d);

        return $d == ['domain' => DOMAIN, 'email' => 'sales', 'password' => 'longenough1', 'quota' => 0, 'send_welcome_email' => 0]
            && $r->header('Authorization')[0] === 'cpanel acct:TESTTOKEN';
    });
});

test('username is lower-cased', function () {
    fakeCpanel();
    $this->actingAs(client())->post('/mailbox/add', [
        'username' => 'Sales', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertSessionHasNoErrors();
    Http::assertSent(fn (Request $r) => str_contains($r->url(), 'add_pop') && $r['email'] === 'sales');
});

test('bad usernames are refused', function (string $name) {
    fakeCpanel();
    $this->actingAs(client())->post('/mailbox/add', [
        'username' => $name, 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertSessionHasErrors('username');
    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), 'add_pop'));
})->with(['..x', 'a b', 'cpanel', str_repeat('a', 65)]);

test('reset password sends email, domain, password to passwd_pop', function () {
    fakeCpanel([row('sales@'.DOMAIN)]);
    $this->actingAs(client())->post('/mailbox/sales@'.DOMAIN.'/password', [
        'password' => 'newpassword9', 'password_confirmation' => 'newpassword9',
    ])->assertRedirect('/mailbox');

    Http::assertSent(function (Request $r) {
        if (! str_contains($r->url(), 'Email/passwd_pop')) {
            return false;
        }
        $d = $r->data();
        ksort($d);

        return $d == ['domain' => DOMAIN, 'email' => 'sales', 'password' => 'newpassword9'];
    });
});

test('reset on another domain address is refused', function () {
    fakeCpanel();
    $this->actingAs(client())->post('/mailbox/boss@other-client.com/password', [
        'password' => 'newpassword9', 'password_confirmation' => 'newpassword9',
    ])->assertNotFound();
    Http::assertNothingSent();
});

test('status 0 shows the friendly message and logs once without the password', function () {
    fakeCpanel([], ['status' => 0, 'errors' => ['Internal secret detail'], 'data' => null]);
    Log::spy();

    $this->actingAs(client())->from('/mailbox')->post('/mailbox/add', [
        'username' => 'sales', 'password' => 'secretpass99', 'password_confirmation' => 'secretpass99',
    ])->assertRedirect('/mailbox')->assertSessionHas('error', 'Mailbox service is unavailable right now. iMotion has been notified.');

    Log::shouldHaveReceived('warning')->once()->withArgs(function ($msg, $ctx) {
        return ! str_contains(json_encode([$msg, $ctx]), 'secretpass99')
            && ! str_contains(json_encode([$msg, $ctx]), 'TESTTOKEN');
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
    fakeCpanel($rows);
    $user = client();

    $this->actingAs($user)->get('/mailbox')->assertOk()->assertDontSee('Add mailbox');
    $this->actingAs($user)->post('/mailbox/add', [
        'username' => 'extra', 'password' => 'longenough1', 'password_confirmation' => 'longenough1',
    ])->assertStatus(422);
    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), 'add_pop'));
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
