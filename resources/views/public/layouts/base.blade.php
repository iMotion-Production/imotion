<?php
    $title = ($title ?? 'Page') . ' - ' . config('app.name');
    $descriptions = $descriptions ?? 'iMotion Production is a creative digital marketing agency that helps your business grow through graphic design, SEO, social media management, web & app development, and video animation. We craft engaging digital experiences to boost conversions.';
    $keywords = $keywords ?? 'digital marketing, creative agency, SEO services, social media management, graphic design, website development, mobile app development, video animation, iMotion Production, digital agency Malaysia, online marketing, branding';
    $hasGTM = config('app.gtm_enabled') && config('app.gtm_id') !== '';
    $hasGA = config('app.ga_enabled') && config('app.ga_id') !== '';
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="title" content="{{ $title }}" />
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="{{ $title }}" />
    <link rel="manifest" href="/site.webmanifest" />
@if (isset($hideSEO) && $hideSEO)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="description" content="{{ $descriptions }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="title" content="{{ $title }}" />
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">
@endif
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="copyright" content="{{ date('Y') }}">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:title" content="{{ $title }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite('resources/css/public.css')
    <script type="text/javascript">function setTheme(e=!1){window.localStorage.setItem("theme",e?"dark":"light");const t=document.documentElement.classList;e?t.add("dark"):t.remove("dark")}"dark"===window.localStorage.getItem("theme")||!("theme"in window.localStorage)&&window.matchMedia("(prefers-color-scheme: dark)").matches?setTheme(!0):setTheme(!1)</script>
    @if ($hasGA)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.gtm_id') }}"></script>
    <script>function gtag(){dataLayer.push(arguments)}window.dataLayer=window.dataLayer||[],gtag("js",new Date),gtag("config", '{{ config('app.ga_id') }}');</script>
    @endif
    @if ($hasGTM)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer', '{{ config('app.gtm_id') }}');</script>
    @endif
</head>
<body class="antialiased overflow-x-hidden font-montserrat overflow-y-hidden">
@if ($hasGTM)
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('app.gtm_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
<div x-data="loader()" x-show="isLoading" class="loader" x-transition:leave="transition ease-in-out duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="item"></div>
    <span>Loading...</span>
</div>
@include('public/partials/header')
@yield('content')
@include('public/partials/footer')
@isset($script)
@vite('resources/js/blade/pages/' . $script . '.js')
@endisset
@vite('resources/js/blade/layouts/base.js')
</body>
</html>
