<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="{{ $branding['primary_color'] ?? '#FF5A5F' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <title>@yield('title', $branding['app_name'] ?? 'HOSTY')</title>
    @if(request()->routeIs('tenant.*') || request()->is('khach') || request()->is('khach/*'))
        <meta name="robots" content="noindex, nofollow">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>
        :root {
            --color-primary: {{ $branding['primary_color'] ?? '#FF5A5F' }};
            --color-primary-hover: #E84E52;
            --color-primary-soft: #FFF0F0;
        }
    </style>
</head>
<body class="font-sans antialiased">
    @yield('body')
    @stack('scripts')
</body>
</html>
