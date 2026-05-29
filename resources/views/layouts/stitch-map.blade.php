<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
@php
    $seoMeta = $seo ?? \App\Support\SeoData::forMap($branding ?? []);
@endphp
<title>{{ $seoMeta->title }}</title>
<x-seo.meta :seo="$seoMeta" :branding="$branding ?? []" />
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@include('components.stitch.tailwind-config-map')
<style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .map-gradient {
            background: radial-gradient(circle at 50% 50%, #f0f3ff 0%, #dce2f3 100%);
        }
        #map.leaflet-container { background: transparent; font-family: Inter, sans-serif; }
        #map .leaflet-tile-pane { opacity: 0.92; }
    </style>
@include('components.stitch.motion-styles')
@stack('head')
</head>
<body x-data="{}" class="bg-surface text-on-surface flex flex-col h-screen overflow-hidden">
    @yield('body')
    @vite(['resources/js/app.js', 'resources/js/map.js'])
    @stack('scripts')
</body>
</html>
