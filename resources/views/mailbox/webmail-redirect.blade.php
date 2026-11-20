<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <title>Opening webmail</title>
    <style>body { margin: 0; padding: 32px 16px; background: #030615; color: #F9FAFB; font: 16px/1.5 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; } button { margin-top: 8px; padding: 10px 18px; font: inherit; font-weight: 600; color: #030615; background: #F9FAFB; border: 0; border-radius: 6px; cursor: pointer; }</style>
</head>
<body>
<form id="webmail-login" method="POST" action="{{ $action }}">
    <input type="hidden" name="session" value="{{ $session }}">
    <p>Opening webmail…</p>
    <button type="submit">Continue</button>
</form>
<script>
document.getElementById('webmail-login').submit();
</script>
</body>
</html>
