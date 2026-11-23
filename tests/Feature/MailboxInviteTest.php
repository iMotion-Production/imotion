<?php

use App\Http\Controllers\Mailbox\MailboxController;
use App\Models\MailboxInvite;
use App\Models\User;
use App\Services\Cpanel\CpanelTransport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Fakes\FakeCpanelTransport;

uses(RefreshDatabase::class);

const INV_DOMAIN = 'fokusteguhdinamik.com.my';
const INV_HOST = 'mailbox.fokusteguhdinamik.com.my';

/** The exact sentence cPanel's Email/passwd_pop returns for a weak password (same as MailboxPortalTest). */
const INV_WEAK_REPLY = 'The password that you entered has a strength rating of "1". You cannot use it because it is too weak and too easy to guess. Please enter a password with a strength rating of "65" or higher.';

beforeEach(function () {
    config(['services.cpanel.max_mailboxes' => 20, 'app.url' => 'https://imotion.my']);
});

function invAdmin(string $host = INV_HOST): User
{
    return User::factory()->create(['mail_domain' => INV_DOMAIN, 'portal_host' => $host]);
}

function invRow(string $local): array
{
    $email = $local.'@'.INV_DOMAIN;

    return ['email' => $email, 'login' => $email, 'user' => $local, 'domain' => INV_DOMAIN, 'humandiskused' => '1 MB', 'humandiskquota' => 'None', '_diskused' => 1048576];
}

function invFake(array $rows = [], array $writeReply = ['status' => 1, 'errors' => null, 'data' => null]): FakeCpanelTransport
{
    $fake = new FakeCpanelTransport($rows, $writeReply);
    app()->instance(CpanelTransport::class, $fake);

    return $fake;
}

/** A saved invite row for $admin, returning [row, plain token]. */
function invMake(User $admin, string $local = 'sales', string $domain = INV_DOMAIN, ?int $hours = 72): array
{
    $token = Str::random(48);

    $invite = MailboxInvite::create([
        'user_id' => $admin->id,
        'mail_domain' => $domain,
        'mailbox_user' => $local,
        'token_hash' => hash('sha256', $token),
        'expires_at' => now()->addHours($hours),
    ]);

    return [$invite, $token];
}

test('admin invite links to the portal host and stores only the hash', function () {
    $fake = invFake([invRow('sales')]);
    $admin = invAdmin();

    $res = $this->actingAs($admin)
        ->from('/mailbox')
        ->post('/mailbox/sales@'.INV_DOMAIN.'/invite')
        ->assertRedirect('/mailbox')
        ->assertSessionHas('invite_for', 'sales@'.INV_DOMAIN);

    $link = session('invite_link');
    expect($link)->toMatch('#^https://mailbox\.fokusteguhdinamik\.com\.my/mailbox/join/[A-Za-z0-9]{48}$#');

    $token = substr($link, -48);

    $rows = DB::table('mailbox_invites')->get();
    expect($rows)->toHaveCount(1)
        ->and($rows[0]->token_hash)->toBe(hash('sha256', $token))
        ->and($rows[0]->mail_domain)->toBe(INV_DOMAIN)
        ->and($rows[0]->mailbox_user)->toBe('sales');

    // The plain token is nowhere in the table.
    $plain = json_encode(DB::table('mailbox_invites')->get());
    expect($plain)->not->toContain($token);
});

test('invite for another domain address is 404 and stores nothing', function () {
    $fake = invFake([invRow('sales')]);

    $this->actingAs(invAdmin())
        ->post('/mailbox/boss@other-client.com/invite')
        ->assertNotFound();

    expect($fake->calls)->toBe([])
        ->and(MailboxInvite::count())->toBe(0);
});

test('invite for a mailbox not in the list gives the error and stores nothing', function () {
    invFake([invRow('info')]);

    $this->actingAs(invAdmin())
        ->from('/mailbox')
        ->post('/mailbox/sales@'.INV_DOMAIN.'/invite')
        ->assertRedirect('/mailbox')
        ->assertSessionHas('error', 'This mailbox no longer exists.');

    expect(MailboxInvite::count())->toBe(0);
});

test('a second invite replaces the first unused one and the first link is 410', function () {
    invFake([invRow('sales')]);
    $admin = invAdmin();

    $this->actingAs($admin)->from('/mailbox')->post('/mailbox/sales@'.INV_DOMAIN.'/invite');
    $first = substr(session('invite_link'), -48);

    $this->actingAs($admin)->from('/mailbox')->post('/mailbox/sales@'.INV_DOMAIN.'/invite');
    $second = substr(session('invite_link'), -48);

    expect($second)->not->toBe($first)
        ->and(MailboxInvite::count())->toBe(1)
        ->and(MailboxInvite::first()->token_hash)->toBe(hash('sha256', $second));

    $this->get('https://'.INV_HOST.'/mailbox/join/'.$first)->assertStatus(410);
    $this->get('https://'.INV_HOST.'/mailbox/join/'.$second)->assertOk();
});

test('the join page on the client host shows the form with the security headers', function () {
    $admin = invAdmin();
    [, $token] = invMake($admin);

    $res = $this->get('https://'.INV_HOST.'/mailbox/join/'.$token)->assertOk();

    $res->assertSee('Set your password')
        ->assertSee('sales@'.INV_DOMAIN)
        ->assertSee('data-generate-password', false)
        ->assertSee('<p class="addr">sales@'.INV_DOMAIN.'</p>', false)
        ->assertSee('<input type="email" name="username" value="sales@'.INV_DOMAIN.'" autocomplete="username" hidden>', false)
        ->assertSee('minlength="12"', false)
        ->assertSee('<button type="submit" class="btn solid">Set password</button>', false);

    $html = $res->getContent();
    expect(substr_count($html, 'minlength="12"'))->toBe(2)
        ->and(substr_count($html, '<p class="addr">'))->toBe(1)
        ->and(substr_count($html, 'name="username"'))->toBe(1)
        ->and(preg_match('/name="password"[^>]*minlength="12"/', $html))->toBe(1);

    expect($res->headers->get('Referrer-Policy'))->toBe('no-referrer')
        ->and($res->headers->get('Cache-Control'))->toContain('no-store')
        ->and($res->headers->get('Cache-Control'))->toContain('private');

    expect(preg_match('/imotion/i', $html))->toBe(0)
        ->and(preg_match('/cpanel/i', $html))->toBe(0);
});

test('the join page on another host is 404', function () {
    [, $token] = invMake(invAdmin());

    $this->get('https://other.example.com/mailbox/join/'.$token)->assertNotFound();
});

test('an unknown token is 410', function () {
    invAdmin();

    $res = $this->get('https://'.INV_HOST.'/mailbox/join/'.Str::random(48))->assertStatus(410);

    $res->assertSee('<p class="msg" role="status">This link has expired or was already used. Ask your administrator for a new one.</p>', false)
        ->assertSee('This link has expired or was already used. Ask your administrator for a new one.')
        ->assertDontSee('<h1', false);

    expect($res->headers->get('Referrer-Policy'))->toBe('no-referrer')
        ->and($res->headers->get('Cache-Control'))->toContain('no-store');

    $html = $res->getContent();
    expect(preg_match('/imotion/i', $html))->toBe(0)
        ->and(preg_match('/cpanel/i', $html))->toBe(0);
});

test('an expired token is 410', function () {
    [, $token] = invMake(invAdmin(), hours: 72);

    $this->travel(73)->hours();

    $this->get('https://'.INV_HOST.'/mailbox/join/'.$token)->assertStatus(410);
});

test('a used token is 410', function () {
    [$invite, $token] = invMake(invAdmin());
    $invite->forceFill(['used_at' => now()])->save();

    $this->get('https://'.INV_HOST.'/mailbox/join/'.$token)->assertStatus(410);
});

test('setting a strong password calls passwd_pop once and opens webmail', function () {
    $fake = invFake([], ['status' => 1, 'errors' => null, 'data' => [
        'hostname' => 'lux.ix-dns.com', 'token' => '/cpsess1234567890', 'session' => 'sales@x:AbC123:CREATE_WEBMAIL_SESSION_FOR_MAIL_USER,deadbeef',
    ]]);
    [$invite, $token] = invMake(invAdmin());

    $res = $this->post('https://'.INV_HOST.'/mailbox/join/'.$token, [
        'password' => 'Stronger-pass-42', 'password_confirmation' => 'Stronger-pass-42',
    ])->assertOk();

    $sent = $fake->sent('passwd_pop');
    expect($sent)->toHaveCount(1);
    $d = $sent[0];
    ksort($d);
    expect($d)->toBe(['domain' => INV_DOMAIN, 'email' => 'sales', 'password' => 'Stronger-pass-42']);

    expect($invite->fresh()->used_at)->not->toBeNull();

    $res->assertSee('Your password is set')
        ->assertSee('<p class="url">https://'.INV_HOST.'/mailbox/webmail</p>', false)
        ->assertSee('https://'.INV_HOST.'/mailbox/webmail')
        ->assertSee('Bookmark this for every day:')
        ->assertSee('<form method="post" action="https://lux.ix-dns.com:2096/cpsess1234567890/login">', false)
        ->assertSee('<input type="hidden" name="session" value="sales@x:AbC123:CREATE_WEBMAIL_SESSION_FOR_MAIL_USER,deadbeef">', false)
        ->assertSee('Open webmail</button>', false);

    // The session form is the button; the plain bookmark link is not rendered alongside it.
    $html = $res->getContent();
    expect(substr_count($html, '<button class="btn solid">Open webmail</button>'))->toBe(1)
        ->and(substr_count($html, '<p class="url">'))->toBe(1)
        ->and(preg_match('/<a class="btn solid" href=/', $html))->toBe(0);

    expect($res->headers->get('Referrer-Policy'))->toBe('no-referrer')
        ->and($res->headers->get('Cache-Control'))->toContain('no-store');

    expect(preg_match('/imotion/i', $html))->toBe(0)
        ->and(preg_match('/cpanel/i', $html))->toBe(0);
});

test('when no webmail session is created the done page offers the bookmark link instead', function () {
    // The fake's default write reply carries no webmail session data, so no one-click login is built.
    invFake([]);
    [$invite, $token] = invMake(invAdmin());

    $res = $this->post('https://'.INV_HOST.'/mailbox/join/'.$token, [
        'password' => 'Stronger-pass-42', 'password_confirmation' => 'Stronger-pass-42',
    ])->assertOk();

    $res->assertSee('<a class="btn solid" href="https://'.INV_HOST.'/mailbox/webmail">Open webmail</a>', false)
        ->assertSee('<p class="url">https://'.INV_HOST.'/mailbox/webmail</p>', false)
        ->assertSee('Bookmark this for every day:');

    $html = $res->getContent();
    expect(preg_match('/<form method="post" action=/', $html))->toBe(0)
        ->and(substr_count($html, '<a class="btn solid" href='))->toBe(1);

    expect($invite->fresh()->used_at)->not->toBeNull();
});

test('a weak password from cPanel keeps the invite usable', function () {
    invFake([], ['status' => 0, 'errors' => [INV_WEAK_REPLY], 'data' => null]);
    [$invite, $token] = invMake(invAdmin());

    $this->from('https://'.INV_HOST.'/mailbox/join/'.$token)
        ->post('https://'.INV_HOST.'/mailbox/join/'.$token, [
            'password' => 'Weak-weak-weak', 'password_confirmation' => 'Weak-weak-weak',
        ])
        ->assertRedirect('https://'.INV_HOST.'/mailbox/join/'.$token)
        ->assertSessionHasErrors(['password' => MailboxController::WEAK_PASSWORD]);

    expect($invite->fresh()->used_at)->toBeNull();
});

test('a mismatched confirmation is a validation error and calls nothing', function () {
    $fake = invFake([]);
    [, $token] = invMake(invAdmin());

    $this->from('https://'.INV_HOST.'/mailbox/join/'.$token)
        ->post('https://'.INV_HOST.'/mailbox/join/'.$token, [
            'password' => 'Stronger-pass-42', 'password_confirmation' => 'Stronger-pass-43',
        ])
        ->assertSessionHasErrors('password');

    $this->from('https://'.INV_HOST.'/mailbox/join/'.$token)
        ->post('https://'.INV_HOST.'/mailbox/join/'.$token, [
            'password' => 'short', 'password_confirmation' => 'short',
        ])
        ->assertSessionHasErrors('password');

    expect($fake->calls)->toBe([]);
});

test('the index page offers Invite for each mailbox: address line, three-dot menu and links row', function () {
    invFake([invRow('sales'), invRow('support')]);

    $res = $this->actingAs(invAdmin())->get('/mailbox');
    $res->assertOk();

    foreach (['sales', 'support'] as $local) {
        $address = $local.'@'.INV_DOMAIN;
        $action = route('mailbox.invite', ['address' => $address]);

        // One in the address cell, one in the menu, one in the phone links row.
        expect(substr_count($res->getContent(), '<form class="invite" method="POST" action="'.e($action).'">'))->toBe(3);
    }

    // Menu order: Invite, Reset password, Open webmail, Delete.
    $pop = substr($res->getContent(), strpos($res->getContent(), '<div class="pop">'));
    $pop = substr($pop, 0, strpos($pop, '</details>'));
    $order = ['>Invite<', '>Reset password<', '>Open webmail<', '>Delete<'];
    $at = -1;
    foreach ($order as $label) {
        $pos = strpos($pop, $label);
        expect($pos)->not->toBeFalse()->toBeGreaterThan($at);
        $at = $pos;
    }

    // The same order in the phone links row.
    $links = substr($res->getContent(), strpos($res->getContent(), '<div class="links">'));
    $links = substr($links, 0, strpos($links, '</div>'));
    $at = -1;
    foreach (['>Invite<', '>Reset password<', '>Open webmail<', '>Delete<'] as $label) {
        $pos = strpos($links, $label);
        expect($pos)->not->toBeFalse()->toBeGreaterThan($at);
        $at = $pos;
    }
});

test('after an invite the index shows that mailbox invite link once, with a copy button and the hint', function () {
    invFake([invRow('sales'), invRow('support')]);
    $admin = invAdmin();

    $this->actingAs($admin)->from('/mailbox')->post('/mailbox/sales@'.INV_DOMAIN.'/invite')->assertRedirect('/mailbox');
    $link = session('invite_link');
    expect($link)->toBeString();

    $res = $this->actingAs($admin)->get('/mailbox')->assertOk();

    $res->assertSee('Invite link for sales@'.INV_DOMAIN, false)
        ->assertSee('value="'.e($link).'" readonly', false)
        ->assertSee('data-copy="#invite-link"', false)
        ->assertSee('Copy link')
        ->assertSee('Send this to the person. It works once and expires in 72 hours.');

    // Only that mailbox's row: one panel and one link value on the page.
    $res->assertSee('class="panel invite-panel"', false)
        ->assertSee('value="'.e($link).'" readonly', false)
        ->assertSee('data-copy="#invite-link"', false)
        ->assertSee('>Copy link<', false);
    expect(substr_count($res->getContent(), 'value="'.e($link).'" readonly'))->toBe(1)
        ->and($res->getContent())->not->toContain('Invite link for support@'.INV_DOMAIN);
});

test('the invite-link panel is gone on the next page load and the link is not in the HTML', function () {
    invFake([invRow('sales')]);
    $admin = invAdmin();

    $this->actingAs($admin)->from('/mailbox')->post('/mailbox/sales@'.INV_DOMAIN.'/invite');
    $link = session('invite_link');

    $this->actingAs($admin)->get('/mailbox')->assertSee('Invite link for sales@'.INV_DOMAIN, false);

    $res = $this->actingAs($admin)->get('/mailbox')->assertOk();

    $res->assertDontSee('Invite link for sales@'.INV_DOMAIN, false)
        ->assertDontSee('data-copy="#invite-link"', false)
        ->assertDontSee('Send this to the person.', false)
        ->assertDontSee(substr($link, -48), false);
});

test('the client-host index has no iMotion wording', function () {
    invFake([invRow('sales')]);
    User::factory()->create(['email' => 'owner@'.INV_DOMAIN, 'mail_domain' => INV_DOMAIN, 'portal_host' => INV_HOST]);

    $res = $this->actingAs(User::where('mail_domain', INV_DOMAIN)->first())
        ->get('https://'.INV_HOST.'/mailbox')
        ->assertOk();

    $res->assertDontSee('iMotion');
    expect(stripos($res->getContent(), 'imotion'))->toBeFalse();
});

test('the token never reaches the log', function () {
    invFake([], ['status' => 0, 'errors' => ['Internal secret detail'], 'data' => null]);
    Log::spy();
    [, $token] = invMake(invAdmin());

    $this->get('https://'.INV_HOST.'/mailbox/join/'.$token);
    $this->from('https://'.INV_HOST.'/mailbox/join/'.$token)
        ->post('https://'.INV_HOST.'/mailbox/join/'.$token, [
            'password' => 'Stronger-pass-42', 'password_confirmation' => 'Stronger-pass-42',
        ]);

    Log::shouldHaveReceived('warning')->once()->withArgs(function ($msg, $ctx) {
        return $ctx['function'] === 'Email/passwd_pop'
            && $ctx['mailbox'] === 'sales@'.INV_DOMAIN;
    });

    Log::shouldNotHaveReceived('warning', fn ($msg, $ctx) => str_contains(json_encode([$msg, $ctx ?? []]), $token));
    Log::shouldNotHaveReceived('info', fn ($msg, $ctx) => str_contains(json_encode([$msg, $ctx ?? []]), $token));
    Log::shouldNotHaveReceived('debug', fn ($msg, $ctx) => str_contains(json_encode([$msg, $ctx ?? []]), $token));
});