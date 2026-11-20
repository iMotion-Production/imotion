<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Mailboxes') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <style>
        :root { --bg: #030615; --fg: #F9FAFB; --muted: #9ca3af; --line: #3e4247; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--fg); font: 16px/1.5 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
        main { max-width: 720px; margin: 0 auto; padding: 32px 16px 48px; }
        header { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 24px; }
        header svg { height: 28px; width: auto; color: var(--fg); }
        h1 { font-size: 1.5rem; line-height: 1.2; margin: 0 0 16px; overflow-wrap: anywhere; }
        h2 { font-size: 1.125rem; margin: 32px 0 12px; }
        a { color: var(--fg); }
        p.muted, .muted { color: var(--muted); font-size: .875rem; }
        table { width: 100%; border-collapse: collapse; font-size: .9375rem; }
        th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid var(--line); vertical-align: top; }
        th { color: var(--muted); font-weight: 600; font-size: .8125rem; }
        td.addr { overflow-wrap: anywhere; }
        label { display: block; font-size: .875rem; margin: 12px 0 4px; }
        input[type=email], input[type=password], input[type=text] { width: 100%; padding: 10px 12px; font: inherit; color: var(--fg); background: #0b0f1f; border: 1px solid var(--line); border-radius: 6px; }
        input:focus-visible, button:focus-visible, summary:focus-visible { outline: 2px solid var(--fg); outline-offset: 2px; }
        .suffix { display: flex; align-items: center; gap: 6px; }
        .suffix span { color: var(--muted); white-space: nowrap; }
        button { margin-top: 16px; padding: 10px 18px; font: inherit; font-weight: 600; color: var(--bg); background: var(--fg); border: 0; border-radius: 6px; cursor: pointer; }
        button.link { margin: 0; padding: 0; background: none; color: var(--muted); font-weight: 400; text-decoration: underline; }
        .show { display: flex; align-items: center; gap: 6px; margin-top: 8px; font-size: .8125rem; color: var(--muted); }
        details summary { cursor: pointer; color: var(--muted); font-size: .875rem; }
        details form { margin-top: 4px; }
        .flash { padding: 10px 12px; border: 1px solid var(--line); border-radius: 6px; margin-bottom: 16px; }
        .flash.error, .err { color: #fca5a5; }
        .err { font-size: .8125rem; margin: 4px 0 0; }
        footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid var(--line); color: var(--muted); font-size: .8125rem; overflow-wrap: anywhere; }
        @media (max-width: 480px) { th.q, td.q { display: none; } }
    </style>
</head>
<body>
<main>
    <header>
        <x-logo aria-label="{{ config('app.name') }}" role="img" />
        @auth
            <form method="POST" action="{{ route('mailbox.logout') }}">
                @csrf
                <button type="submit" class="link">Log out</button>
            </form>
        @endauth
    </header>
    @yield('content')
</main>
<script>
document.querySelectorAll('[data-show-password]').forEach(function (box) {
    box.addEventListener('change', function () {
        box.closest('form').querySelectorAll('input[data-pw]').forEach(function (i) { i.type = box.checked ? 'text' : 'password'; });
    });
});
</script>
</body>
</html>
