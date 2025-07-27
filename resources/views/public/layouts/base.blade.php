<?php
    $full_title = ($title ?? 'Page') . ' - ' . config('app.name');
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $full_title }}</title>
    <meta name="title" content="{{ $full_title }}" />
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="copyright" content="{{ date('Y') }}">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:title" content="{{ $full_title }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite('resources/css/public.css')
    <script type="text/javascript">function setTheme(e=!1){window.localStorage.setItem("theme",e?"dark":"light");const t=document.documentElement.classList;e?t.add("dark"):t.remove("dark")}"dark"===window.localStorage.getItem("theme")||!("theme"in window.localStorage)&&window.matchMedia("(prefers-color-scheme: dark)").matches?setTheme(!0):setTheme(!1)</script>
</head>
<body class="antialiased overflow-x-hidden font-montserrat">
@include('public/partials/header')
@yield('content')
@include('public/partials/footer')
@isset($script)
@vite('resources/js/blade/pages/' . $script . '.js')
@endisset
@vite('resources/js/blade/layouts/base.js')
</script>
</body>
</html>
