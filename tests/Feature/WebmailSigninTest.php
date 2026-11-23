<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

const WEBMAIL_HOST = 'mailbox.fokusteguhdinamik.com.my';
const WEBMAIL_DOMAIN = 'fokusteguhdinamik.com.my';

beforeEach(function () {
    config(['app.url' => 'https://imotion.my']);
});

test('webmail sign-in page renders for a client host, logged out', function () {
    User::factory()->create(['mail_domain' => WEBMAIL_DOMAIN, 'portal_host' => WEBMAIL_HOST]);

    $this->get('https://'.WEBMAIL_HOST.'/mailbox/webmail')->assertOk()
        ->assertSee('action="https://'.WEBMAIL_DOMAIN.':2096/login/"', false)
        ->assertSee('name="user"', false)
        ->assertSee('name="pass"', false)
        ->assertSee('Webmail')
        ->assertSee('Email address')
        ->assertSee('Password')
        ->assertSee('Show password')
        ->assertSee('Log in')
        ->assertSee('Forgot your password? Contact your administrator.')
        ->assertSee('class="auth has-bg"', false)
        ->assertSee('<title>Webmail</title>', false);
});

test('webmail sign-in page carries no csrf token, no iMotion and no cPanel wording', function () {
    User::factory()->create(['mail_domain' => WEBMAIL_DOMAIN, 'portal_host' => WEBMAIL_HOST]);

    $html = $this->get('https://'.WEBMAIL_HOST.'/mailbox/webmail')->assertOk()->getContent();
    expect($html)->not->toContain('_token')
        ->and(preg_match('/csrf/i', $html))->toBe(0)
        ->and(preg_match('/imotion/i', $html))->toBe(0)
        ->and(preg_match('/cpanel/i', $html))->toBe(0);
});

test('webmail sign-in page still renders for a logged in client user', function () {
    $user = User::factory()->create(['mail_domain' => WEBMAIL_DOMAIN, 'portal_host' => WEBMAIL_HOST]);

    $this->actingAs($user)->get('https://'.WEBMAIL_HOST.'/mailbox/webmail')->assertOk()
        ->assertSee('action="https://'.WEBMAIL_DOMAIN.':2096/login/"', false);
});

test('webmail sign-in page is 404 on the iMotion host', function () {
    $this->get('https://imotion.my/mailbox/webmail')->assertNotFound();
});

test('webmail sign-in page is 404 on a client host with no user row', function () {
    $this->get('https://nobody.example.com/mailbox/webmail')->assertNotFound();
});

test('a POST to the webmail sign-in path on a client host is not allowed', function () {
    User::factory()->create(['mail_domain' => WEBMAIL_DOMAIN, 'portal_host' => WEBMAIL_HOST]);

    $this->post('https://'.WEBMAIL_HOST.'/mailbox/webmail')->assertStatus(405);
});

test('the client login page title is unchanged', function () {
    User::factory()->create(['mail_domain' => WEBMAIL_DOMAIN, 'portal_host' => WEBMAIL_HOST]);

    $this->get('https://'.WEBMAIL_HOST.'/mailbox/login')->assertOk()->assertSee('Mailboxes');
});