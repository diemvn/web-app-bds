<!DOCTYPE html>
<html class="light" lang="vi">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport">
<meta name="theme-color" content="#003fb1">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="manifest" href="/manifest.json">
@php
    $seoMeta = $seo ?? \App\Support\SeoData::forHome($branding ?? []);
@endphp
<title>{{ $seoMeta->title }}</title>
<x-seo.meta :seo="$seoMeta" :branding="$branding ?? []" />
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@include('components.stitch.tailwind-config')
@include('components.stitch.base-styles')
@stack('head')
</head>
<body x-data="{}" class="@yield('body_class', 'bg-background text-on-background font-body-md min-h-screen')">
    @if(session('success'))
        <div class="fixed top-4 left-4 right-4 z-[100] mx-auto max-w-md rounded-xl bg-tertiary-container px-4 py-3 text-sm text-on-tertiary text-center shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @yield('body')
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
