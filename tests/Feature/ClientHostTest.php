<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\Fakes\FakeCpanelTransport;
use App\Services\Cpanel\CpanelTransport;

uses(RefreshDatabase::class);

const CLIENT_HOST = 'mailbox.fokusteguhdinamik.com.my';
const FTD_DOMAIN = 'fokusteguhdinamik.com.my';
const FTD_LOGO = '/mailbox/logos/fokusteguhdinamik.com.my.png';

beforeEach(function () {
    config(['app.url' => 'https://imotion.my', 'services.cpanel.max_mailboxes' => 20]);
    app()->instance(CpanelTransport::class, new FakeCpanelTransport([], ['status' => 1, 'errors' => null, 'data' => null]));
});

test('client host: marketing page is 404 and never renders', function () {
    $this->get('https://'.CLIENT_HOST.'/about')->assertNotFound()->assertDontSee('<html', false);
});

test('client host: / redirects to /mailbox/login', function () {
    $this->get('https://'.CLIENT_HOST.'/')->assertRedirect('/mailbox/login');
});

test('client host: mailbox routes are served', function () {
    $this->get('https://'.CLIENT_HOST.'/mailbox/login')->assertOk();
});

test('app host and www still serve the marketing site', function () {
    $this->get('https://imotion.my/mailbox/login')->assertOk();
    $this->get('https://www.imotion.my/about')->assertOk();
});

test('mailbox:client --host stores portal_host and prints the link on that host', function () {
    $this->artisan('mailbox:client', ['email' => 'fokusteguhdinamik@gmail.com', 'domain' => FTD_DOMAIN, '--name' => 'Fokus Teguh Dinamik', '--host' => CLIENT_HOST])
        ->expectsOutputToContain('https://'.CLIENT_HOST.'/mailbox/reset-password/')
        ->assertSuccessful();
    expect(User::where('email', 'fokusteguhdinamik@gmail.com')->value('portal_host'))->toBe(CLIENT_HOST);
});

test('mailbox:client without --host prints the link on APP_URL', function () {
    $this->artisan('mailbox:client', ['email' => 'a@imotion.my', 'domain' => 'imotion.my'])
        ->expectsOutputToContain('https://imotion.my/mailbox/reset-password/')
        ->assertSuccessful();
    expect(User::where('email', 'a@imotion.my')->value('portal_host'))->toBeNull();
});

test('mailbox:client rejects a bad --host', function () {
    $this->artisan('mailbox:client', ['email' => 'a@x.com', 'domain' => 'x.com', '--host' => 'bad host/..'])->assertFailed();
});

test('dashboard shows the client logo for a fokusteguhdinamik.com.my user', function () {
    $this->actingAs(User::factory()->create(['mail_domain' => FTD_DOMAIN]))->get('/mailbox')
        ->assertOk()->assertSee(FTD_LOGO, false)->assertSee('alt="'.FTD_DOMAIN.'"', false);
});

test('dashboard shows the iMotion mark for an imotion.my user', function () {
    $this->actingAs(User::factory()->create(['mail_domain' => 'imotion.my']))->get('/mailbox')
        ->assertOk()->assertDontSee('/mailbox/logos/', false)->assertSee('aria-label="'.config('app.name').'"', false);
});

test('login page on the client host shows the client logo; on imotion.my the iMotion mark', function () {
    User::factory()->create(['mail_domain' => FTD_DOMAIN, 'portal_host' => CLIENT_HOST]);
    $this->get('https://'.CLIENT_HOST.'/mailbox/login')->assertOk()->assertSee(FTD_LOGO, false);
    $this->get('https://imotion.my/mailbox/login')->assertOk()->assertDontSee('/mailbox/logos/', false);
});
