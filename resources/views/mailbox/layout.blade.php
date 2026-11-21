<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    @php
        // Branding: a client host or a non-imotion.my user sees the client's name and logo, never iMotion's (PM, 2026-11-20).
        $isClient = \App\Support\Portal::isClient();
        $brandUser = \App\Support\Portal::brandUser();
        $logoDomain = $brandUser?->mail_domain;
        $logoFile = $logoDomain && preg_match('/^[a-z0-9.-]+$/', $logoDomain) ? 'portal/logos/'.$logoDomain.'.png' : null;
        if ($logoFile && ! is_file(public_path($logoFile))) {
            $logoFile = null;
        }
        $clientName = $isClient && $brandUser && trim((string) $brandUser->name) !== '' ? trim((string) $brandUser->name) : null;
        // MP-03: photo background on the login and set-password screens only. The domain comes from the same server-side
        // mapping as the logo, never from the URL; no file for a domain means the plain MP-02 page.
        $bgBase = $logoDomain && preg_match('/^[a-z0-9.-]+$/', $logoDomain) && $__env->hasSection('auth') ? 'portal/backgrounds/'.$logoDomain : null;
        $bgFile = $bgBase && is_file(public_path($bgBase.'.webp')) ? $bgBase.'.webp' : null;
        $bgSmallFile = $bgFile && is_file(public_path($bgBase.'-1280.webp')) ? $bgBase.'-1280.webp' : $bgFile;
        $bgPortraitFile = $bgFile && is_file(public_path($bgBase.'-portrait.webp')) ? $bgBase.'-portrait.webp' : $bgSmallFile;
        $bgStyle = $bgFile ? "--auth-bg:url('/".$bgFile."'); --auth-bg-sm:url('/".$bgSmallFile."'); --auth-bg-portrait:url('/".$bgPortraitFile."');" : null;
    @endphp
    @if ($isClient)
    <title>{{ $clientName ? $clientName.' · Mailboxes' : 'Mailboxes' }}</title>
    @php
        // Always name an icon on a client host: with no link the browser asks for /favicon.ico, which is iMotion's.
        $iconFile = $logoDomain && preg_match('/^[a-z0-9.-]+$/', $logoDomain) ? 'portal/favicons/'.$logoDomain.'.png' : null;
    @endphp
    @if ($iconFile && is_file(public_path($iconFile)))
    <link rel="icon" type="image/png" href="/{{ $iconFile }}">
    @else
    <link rel="icon" href="data:,">
    @endif
    @else
    <title>@yield('title', 'Mailboxes') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Mailbox Portal - MP-02 (light app theme after the Project Manager's admin "Users" reference, PM 20:55:
           "make it white background", "same as this", "colour, font, icon same"). Replaces the old inline <style> in
           mailbox/layout.blade.php. Contrast ratios are in tokens-v2.json. No gold. */
        :root {
          --page: #FFFFFF; --card: #FFFFFF; --sunk: #F9FAFB;
          --ink: #111827; --ink-2: #6B7280; --line: #E5E7EB; --field: #D1D5DB;
          --violet: #7C3AED; --violet-hover: #6D28D9; --violet-bg: #F3EEFF; --violet-fg: #6D28D9;
          --neutral-bg: #F3F4F6; --neutral-fg: #374151;
          --blue-bg: #EFF6FF; --blue-fg: #1D4ED8;
          --ok-bg: #ECFDF3; --ok-fg: #067647;
          --warn-bg: #FFFAEB; --warn-fg: #B54708;
          --err: #B42318; --err-bg: #FEF3F2; --err-line: #FECDCA;
          --r: 12px; --r-s: 8px; --tap: 44px;
          --font: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        * { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }
        body { margin: 0; background: var(--page); color: var(--ink); font: 400 14px/1.5 var(--font); }
        a { color: var(--ink); }
        svg.i { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; flex: none; }

        .bar { border-bottom: 1px solid var(--line); }
        .bar > div { max-width: 1200px; margin: 0 auto; padding: 0 20px; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .bar svg.logo { height: 26px; width: auto; color: var(--ink); display: block; }
        main { max-width: 1200px; margin: 0 auto; padding: 28px 20px 48px; }
        h1 { font-size: 1.375rem; line-height: 1.25; font-weight: 600; letter-spacing: -.015em; margin: 0 0 20px; overflow-wrap: anywhere; }
        h1 { display: flex; align-items: center; gap: 8px; }
        h1 svg.i { width: 18px; height: 18px; color: var(--ink-2); }
        h2 { font-size: 1rem; font-weight: 600; margin: 0 0 4px; }

        .stats { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 0 0 24px; }
        .stat { border: 1px solid var(--line); border-radius: var(--r); padding: 16px; display: flex; gap: 14px; align-items: center; min-width: 0; }
        .tile { width: 44px; height: 44px; border-radius: 10px; display: grid; place-items: center; flex: none; }
        .tile.v { background: var(--violet-bg); color: var(--violet); }
        .tile.n { background: var(--neutral-bg); color: var(--neutral-fg); }
        .tile svg.i { width: 22px; height: 22px; }
        .stat dt { font-size: .8125rem; color: var(--ink-2); font-weight: 500; }
        .stat dd { margin: 2px 0 0; font-size: 1.5rem; line-height: 1.2; font-weight: 600; letter-spacing: -.02em; white-space: nowrap; }

        .toolbar { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .domain { display: inline-flex; align-items: center; gap: 8px; min-height: 40px; padding: 8px 14px; border: 1px solid var(--line); border-radius: 999px; font-weight: 500; overflow-wrap: anywhere; }
        .domain svg.i { color: var(--ink-2); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: var(--tap); padding: 10px 16px; font: inherit; font-weight: 600; text-decoration: none; border-radius: var(--r-s); cursor: pointer; border: 1px solid transparent; }
        .btn.primary { background: var(--violet-bg); color: var(--violet-fg); }
        .btn.primary:hover { background: #E9E0FF; }
        body:has(.panel.open) .btn.primary[aria-controls=add] { background: #E9E0FF; box-shadow: inset 0 0 0 1px #D9CCFF; }
        .btn.solid { background: var(--violet); color: #fff; }
        .btn.solid:hover { background: var(--violet-hover); }
        button.link { margin: 0; min-height: var(--tap); padding: 0 4px; background: none; border: 0; font: inherit; font-weight: 500; color: var(--ink-2); cursor: pointer; }
        button.link:hover { color: var(--ink); text-decoration: underline; }
        .note { margin: 0; color: var(--ink-2); }

        .search { position: relative; margin-bottom: 16px; }
        .search svg.i { position: absolute; left: 14px; top: 13px; color: var(--ink-2); }
        .search input[type=search] { padding-left: 44px; }

        label { display: block; font-size: .8125rem; font-weight: 500; margin: 14px 0 6px; }
        input[type=email], input[type=password], input[type=text], input[type=search] {
          width: 100%; min-height: var(--tap); padding: 10px 14px; font: inherit; color: var(--ink);
          background: #fff; border: 1px solid var(--field); border-radius: var(--r-s); box-shadow: 0 1px 2px rgba(16,24,40,.04); }
        input::placeholder { color: var(--ink-2); }
        input:hover { border-color: #9CA3AF; }
        input[aria-invalid=true] { border-color: var(--err); }
        /* Fix round 1 (W002): the fixed @domain is joined to the input as one field. Phones: a strip directly under the
           input, sharing its border. 720 px and up: an addon on the input's right edge (see the min-width block). */
        .suffix { display: flex; flex-direction: column; }
        .suffix input { border-radius: var(--r-s) var(--r-s) 0 0; position: relative; }
        .suffix input:focus-visible { z-index: 1; }
        .suffix span { display: flex; align-items: center; min-height: 36px; padding: 6px 14px; color: var(--ink-2); background: var(--sunk); border: 1px solid var(--field); border-top: 0; border-radius: 0 0 var(--r-s) var(--r-s); overflow-wrap: anywhere; }
        .suffix:has(input[aria-invalid=true]) span { border-color: var(--err); }
        .show { display: flex; align-items: center; gap: 10px; min-height: var(--tap); margin: 6px 0 0; font-weight: 400; color: var(--ink-2); }
        .show input { width: 18px; height: 18px; margin: 0; accent-color: var(--violet); }
        .err { color: var(--err); font-size: .8125rem; font-weight: 500; margin: 6px 0 0; }
        :focus-visible { outline: 2px solid var(--violet); outline-offset: 2px; }

        .panel { display: none; border: 1px solid var(--line); border-radius: var(--r); padding: 20px; margin-bottom: 16px; background: var(--card); }
        .panel:target, .panel.open { display: block; }
        .panel form { max-width: 520px; }
        .panel .sub { margin: 0; color: var(--ink-2); overflow-wrap: anywhere; }
        .panel .btn { margin-top: 12px; }

        .flash { display: flex; flex-wrap: wrap; gap: 4px 12px; align-items: center; padding: 12px 16px; border-radius: var(--r); margin-bottom: 16px; background: var(--ok-bg); color: var(--ok-fg); border: 1px solid #ABEFC6; font-weight: 500; overflow-wrap: anywhere; }
        .flash a { color: var(--ok-fg); font-weight: 600; }
        .flash.error { background: var(--err-bg); color: var(--err); border-color: var(--err-line); }

        .list { border: 1px solid var(--line); border-radius: var(--r); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: none; }
        tr.row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px 12px; padding: 16px; border-bottom: 1px solid var(--line); }
        tbody tr:last-child { border-bottom: 0; }
        td { display: block; padding: 0; }
        td.addr { grid-column: 1 / -1; overflow-wrap: anywhere; }
        td.addr strong { display: block; font-weight: 600; }
        td.addr .web { display: none; }
        td.used::before, td.q::before, td.st::before { content: attr(data-label); display: block; font-size: .75rem; color: var(--ink-2); margin-bottom: 4px; }
        td.act { grid-column: 1 / -1; }
        td.act .menu { display: none; }
        td.act .links { display: flex; gap: 20px; }
        td.act .links a { display: inline-flex; align-items: center; min-height: var(--tap); font-weight: 600; color: var(--violet-fg); text-decoration: none; }
        td.empty { padding: 32px 16px; text-align: center; color: var(--ink-2); }
        tr.panel-row { display: none; }
        tr.panel-row:target, tr.panel-row.open { display: block; }
        tr.panel-row td { padding: 0 16px 16px; border-bottom: 1px solid var(--line); }
        tr.panel-row .panel { display: block; margin: 0; background: var(--sunk); }

        .pill { display: inline-flex; align-items: center; gap: 4px; padding: 2px 10px 2px 8px; border-radius: 999px; font-size: .75rem; font-weight: 500; line-height: 20px; white-space: nowrap; }
        .pill svg.i { width: 14px; height: 14px; }
        .pill.ok { background: var(--ok-bg); color: var(--ok-fg); }
        .pill.warn { background: var(--warn-bg); color: var(--warn-fg); }
        .pill.blue { background: var(--blue-bg); color: var(--blue-fg); }
        .pill.neutral { background: var(--neutral-bg); color: var(--neutral-fg); }

        footer { margin-top: 28px; color: var(--ink-2); font-size: .8125rem; line-height: 1.6; overflow-wrap: anywhere; }

        body.auth { background: var(--sunk); }
        body.auth main { max-width: 440px; padding-top: 64px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: var(--r); padding: 32px 28px; box-shadow: 0 1px 3px rgba(16,24,40,.06); }
        .card svg.logo { height: 28px; width: auto; color: var(--ink); display: block; margin-bottom: 28px; }
        .card .btn { width: 100%; margin-top: 16px; }
        .card .muted { color: var(--ink-2); margin: 20px 0 0; text-align: center; }

        @media (max-width: 719px) { .toolbar .btn.primary, .toolbar .note { width: 100%; } }
        /* Fix round 1 (W001): one stat card per row below 560 px, so a 4-digit total ("1,234.5 MB") fits its card. */
        @media (max-width: 559px) { .stats { grid-template-columns: 1fr; } }

        @media (min-width: 720px) {
          .suffix { flex-direction: row; }
          .suffix input { flex: 1 1 auto; min-width: 0; border-radius: var(--r-s) 0 0 var(--r-s); }
          .suffix span { flex: none; min-height: var(--tap); border-top: 1px solid var(--field); border-left: 0; border-radius: 0 var(--r-s) var(--r-s) 0; }
          .bar > div, main { padding-left: 32px; padding-right: 32px; }
          h1 { font-size: 1.5rem; }
          .stats { grid-template-columns: repeat(2, minmax(0, 340px)); gap: 16px; }
          .stat { padding: 20px; }
          thead { display: table-header-group; }
          th { text-align: left; font-size: .75rem; font-weight: 500; color: var(--ink-2); padding: 12px 20px; background: var(--sunk); border-bottom: 1px solid var(--line); white-space: nowrap; }
          th span { display: inline-flex; align-items: center; gap: 6px; }
          th svg.i { width: 14px; height: 14px; }
          tr.row { display: table-row; }
          td { display: table-cell; padding: 14px 20px; border-bottom: 1px solid var(--line); vertical-align: middle; }
          tbody tr.row:last-of-type td { border-bottom: 0; }
          td.used::before, td.q::before, td.st::before { content: none; }
          td.used { color: var(--ink-2); }
          td.addr .web { display: inline-block; font-size: .8125rem; color: var(--ink-2); text-decoration: none; }
          td.addr .web:hover { color: var(--violet-fg); text-decoration: underline; }
          td.act { width: 1%; text-align: right; }
          td.act .links { display: none; }
          td.act .menu { display: inline-block; position: relative; }
          th.act { text-align: right; }
          .menu summary { list-style: none; width: var(--tap); height: var(--tap); display: grid; place-items: center; border-radius: var(--r-s); color: var(--ink-2); cursor: pointer; }
          .menu summary::-webkit-details-marker { display: none; }
          .menu summary:hover, .menu[open] summary { background: var(--neutral-bg); color: var(--ink); }
          .menu .pop { position: absolute; right: 0; top: 48px; z-index: 5; min-width: 200px; padding: 6px; text-align: left; background: #fff; border: 1px solid var(--line); border-radius: var(--r-s); box-shadow: 0 12px 24px -4px rgba(16,24,40,.10), 0 4px 8px -2px rgba(16,24,40,.05); }
          .menu .pop a { display: flex; align-items: center; gap: 10px; min-height: var(--tap); padding: 0 12px; border-radius: 6px; text-decoration: none; font-weight: 500; white-space: nowrap; }
          .menu .pop a svg.i { color: var(--ink-2); }
          .menu .pop a:hover { background: var(--neutral-bg); }
          tr.panel-row:target, tr.panel-row.open { display: table-row; }
          tr.panel-row td { padding: 0 20px 20px; }
        }
        /* Client logo slot (hand-off "Client logo slot"). */
        .bar img.logo, .card img.logo { height: 40px; width: auto; max-width: 100%; display: block; }
        .card img.logo { margin: 0 auto 28px; }
        /* Build: "Open webmail" is the existing POST form (one-click webmail login), styled as the design's links. */
        form.webmail { display: inline; margin: 0; }
        form.webmail button { margin: 0; padding: 0; background: none; border: 0; font: inherit; cursor: pointer; }
        td.addr form.webmail button.web { font-size: .8125rem; color: var(--ink-2); }
        td.addr form.webmail button.web:hover { color: var(--violet-fg); text-decoration: underline; }
        td.act .links form.webmail button { display: inline-flex; align-items: center; min-height: var(--tap); font-weight: 600; color: var(--violet-fg); }
        .flash form.webmail button { color: var(--ok-fg); font-weight: 600; text-decoration: underline; }
        @media (max-width: 719px) { td.addr form.webmail { display: none; } }
        @media (min-width: 720px) {
          .menu .pop form.webmail { display: block; }
          .menu .pop form.webmail button { display: flex; align-items: center; gap: 10px; width: 100%; min-height: var(--tap); padding: 0 12px; border-radius: 6px; font-weight: 500; color: var(--ink); white-space: nowrap; text-align: left; }
          .menu .pop form.webmail button svg.i { color: var(--ink-2); }
          .menu .pop form.webmail button:hover { background: var(--neutral-bg); }
        }
        @if ($bgFile)
        /* MP-03: client login background (HANDOFF-MP-03 login-bg.css). Applies only when the body has class "has-bg",
           which the server adds only on a client host that has a background file. The card itself is unchanged. */

        /* Card lift off the photo (all widths). Card size, fill, fields and text stay as approved. */
        body.auth.has-bg .card { box-shadow: 0 24px 48px -12px rgba(11, 26, 57, .30), 0 4px 12px rgba(11, 26, 57, .12); }

        /* Phones and tablets (< 1024 px): the photo turned a quarter (portrait file) fills the screen,
           workers at the bottom, card on the calm concrete at the top. Every word stays on the opaque card.
           Project Manager, 2026-11-21: "the worker should be on bottom which is remove the white background"; "Yes, use it". */
        @media (max-width: 1023px) {
          body.auth.has-bg {
            min-height: 100vh; display: flex; align-items: flex-start;
            background: #d9d6d0 var(--auth-bg-portrait) center bottom / cover no-repeat;
          }
          body.auth.has-bg main { margin: 0 auto; padding: 28px 20px 0; width: 100%; }
        }

        /* Desktop (>= 1024 px): full-bleed photo, workers on the left; card in the calm right side,
           vertically centred, its right edge 8vw from the window edge. */
        @media (min-width: 1024px) {
          body.auth.has-bg {
            min-height: 100vh; display: flex; align-items: center;
            background: var(--sunk) var(--auth-bg-sm) left center / cover no-repeat;
          }
          body.auth.has-bg main { width: 100%; margin: 0 8vw 0 auto; padding-top: 48px; }
        }
        @media (min-width: 1281px), (min-width: 1024px) and (min-resolution: 1.5dppx) {
          body.auth.has-bg { background-image: var(--auth-bg); }
        }
        @endif
    </style>
</head>
<body class="@yield('body', 'app'){{ $bgFile ? ' has-bg' : '' }}"@if ($bgStyle) style="{{ $bgStyle }}"@endif>
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><symbol id="i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6.5 8.5 6.5 8.5-6.5"/></symbol><symbol id="i-disk" viewBox="0 0 24 24"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v12c0 1.7 3.6 3 8 3s8-1.3 8-3V6"/><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/></symbol><symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></symbol><symbol id="i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol><symbol id="i-dots" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.2"/><circle cx="12" cy="12" r="1.2"/><circle cx="12" cy="19" r="1.2"/></symbol><symbol id="i-key" viewBox="0 0 24 24"><circle cx="8" cy="15" r="4"/><path d="m11 12 9-9M17 6l3 3M15 8l2 2"/></symbol><symbol id="i-out" viewBox="0 0 24 24"><path d="M14 4h6v6M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></symbol><symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5"/></symbol><symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3Z"/></symbol><symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></symbol><symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></symbol><symbol id="i-settings" viewBox="0 0 24 24"><path d="M20 7h-9M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></symbol></svg>
@hasSection('auth')
<main>
<div class="card">
    @if ($logoFile)
        <img class="logo" src="/{{ $logoFile }}" alt="{{ $clientName ?? $logoDomain }}" height="40">
    @elseif (! $isClient)
        <x-logo class="logo" aria-label="{{ config('app.name') }}" role="img" />
    @endif
    @yield('content')
</div>
</main>
@else
<header class="bar"><div>
    @if ($logoFile)
        <img class="logo" src="/{{ $logoFile }}" alt="{{ $clientName ?? $logoDomain }}" height="40">
    @elseif (! $isClient)
        <x-logo class="logo" aria-label="{{ config('app.name') }}" role="img" />
    @else
        <span></span>
    @endif
    @auth
        <form method="POST" action="{{ route('mailbox.logout') }}">
            @csrf
            <button type="submit" class="link">Log out</button>
        </form>
    @endauth
</div></header>
<main>
    @yield('content')
</main>
@endif
<script>
document.querySelectorAll('[data-show-password]').forEach(function (box) {
    box.addEventListener('change', function () {
        box.closest('form').querySelectorAll('input[data-pw]').forEach(function (i) { i.type = box.checked ? 'text' : 'password'; });
    });
});
var panel = document.querySelector('[data-open]');
if (panel) panel.addEventListener('click', function () {
    document.querySelector(panel.dataset.open).classList.toggle('open');
});
var q = document.querySelector('[data-search]');
if (q) q.addEventListener('input', function () {
    var v = q.value.trim().toLowerCase();
    document.querySelectorAll('tr.row').forEach(function (r) { r.hidden = v && r.dataset.address.indexOf(v) < 0; });
});
</script>
</body>
</html>
