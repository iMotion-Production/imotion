<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Fakes\FakeCpanelTransport;
use App\Services\Cpanel\CpanelTransport;

uses(RefreshDatabase::class);

const MP03_HOST = 'mailbox.fokusteguhdinamik.com.my';
const MP03_DOMAIN = 'fokusteguhdinamik.com.my';
const MP03_BG = '/portal/backgrounds/fokusteguhdinamik.com.my.webp';
const MP03_BG_1280 = '/portal/backgrounds/fokusteguhdinamik.com.my-1280.webp';

beforeEach(function () {
    config(['app.url' => 'https://imotion.my', 'services.cpanel.max_mailboxes' => 20]);
    app()->instance(CpanelTransport::class, new FakeCpanelTransport([], ['status' => 1, 'errors' => null, 'data' => null]));
});

/** The rendered <body ...> opening tag of a page. */
function mp03BodyTag(string $html): string
{
    expect(preg_match('/<body[^>]*>/', $html, $m))->toBe(1);

    return $m[0];
}

test('MP-03: both background webp files exist and are non-empty', function () {
    foreach ([MP03_BG, MP03_BG_1280] as $path) {
        $file = public_path(ltrim($path, '/'));
        expect(is_file($file))->toBeTrue()->and(filesize($file))->toBeGreaterThan(0);
    }
});

test('MP-03: client host login page carries has-bg and both background files', function () {
    User::factory()->create(['mail_domain' => MP03_DOMAIN, 'portal_host' => MP03_HOST]);

    $html = $this->get('https://'.MP03_HOST.'/mailbox/login')->assertOk()->getContent();

    expect(mp03BodyTag($html))->toContain('has-bg')
        ->and($html)->toContain(MP03_BG)
        ->and($html)->toContain(MP03_BG_1280);
});

test('MP-03: client host set-password page carries has-bg and both background files', function () {
    User::factory()->create(['mail_domain' => MP03_DOMAIN, 'portal_host' => MP03_HOST]);

    $html = $this->get('https://'.MP03_HOST.'/mailbox/reset-password/bad')->assertOk()->getContent();

    expect(mp03BodyTag($html))->toContain('has-bg')
        ->and($html)->toContain(MP03_BG)
        ->and($html)->toContain(MP03_BG_1280);
});

test('MP-03: imotion.my login page has no has-bg and no background path', function () {
    $html = $this->get('https://imotion.my/mailbox/login')->assertOk()->getContent();

    expect(mp03BodyTag($html))->toBe('<body class="auth">')
        ->and($html)->not->toContain('has-bg')
        ->and($html)->not->toContain('/portal/backgrounds/');
});

test('MP-03: a client host whose domain has no background file keeps a plain auth body', function () {
    User::factory()->create(['mail_domain' => 'nodomain.example', 'portal_host' => 'mailbox.nodomain.example']);

    $html = $this->get('https://mailbox.nodomain.example/mailbox/login')->assertOk()->getContent();

    expect(mp03BodyTag($html))->toBe('<body class="auth">')
        ->and($html)->not->toContain('has-bg')
        ->and($html)->not->toContain('/portal/backgrounds/');
});

test('MP-03: the client dashboard is not an auth page and gets no has-bg', function () {
    $html = $this->actingAs(User::factory()->create(['mail_domain' => MP03_DOMAIN, 'name' => 'Fokus Teguh Dinamik']))
        ->get('https://'.MP03_HOST.'/mailbox')->assertOk()->getContent();

    expect(mp03BodyTag($html))->not->toContain('has-bg')
        ->and($html)->not->toContain('/portal/backgrounds/');
});

test('MP-03: client login HTML contains no iMotion wording', function () {
    User::factory()->create(['mail_domain' => MP03_DOMAIN, 'portal_host' => MP03_HOST]);

    $html = $this->get('https://'.MP03_HOST.'/mailbox/login')->assertOk()->getContent();

    expect(preg_match('/imotion/i', $html))->toBe(0);
});

test('MP-03: the portrait photo exists and the client login body names it', function () {
    expect(is_file(public_path('portal/backgrounds/fokusteguhdinamik.com.my-portrait.webp')))->toBeTrue();
    User::factory()->create(['mail_domain' => MP03_DOMAIN, 'portal_host' => MP03_HOST]);

    $html = $this->get('https://'.MP03_HOST.'/mailbox/login')->assertOk()->getContent();

    expect(mp03BodyTag($html))->toContain('--auth-bg-portrait')
        ->and(mp03BodyTag($html))->toContain('fokusteguhdinamik.com.my-portrait.webp');
});
