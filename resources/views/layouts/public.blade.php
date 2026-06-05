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
<!-- TODO: Switch to Vite build for production -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@include('components.stitch.tailwind-config')
@include('components.stitch.base-styles')
@yield('structured_data')
@stack('head')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('favorites', {
            items: JSON.parse(localStorage.getItem('guest_favorites') || '[]'),
            get count() { return this.items.length; },
            has(id) { return this.items.includes(id); },
            toggle(id) {
                if (this.has(id)) {
                    this.items = this.items.filter(i => i !== id);
                } else {
                    this.items.push(id);
                }
                localStorage.setItem('guest_favorites', JSON.stringify(this.items));
                
                // If authenticated, sync with server via fetch
                if (document.head.querySelector('meta[name="auth-check"]')?.content === '1') {
                    fetch(`/yeu-thich/${id}/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).catch(err => console.error('Favorite sync failed', err));
                }
            }
        });
    });
</script>
</head>
<body x-data="{}" class="@yield('body_class', 'bg-background text-on-background font-body-md min-h-screen flex flex-col')">
    @auth
        <meta name="auth-check" content="1">
    @endauth

    @if(session('success'))
        <div class="fixed top-4 left-4 right-4 z-[100] mx-auto max-w-md rounded-xl bg-tertiary-container px-4 py-3 text-sm text-on-tertiary text-center shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @unless(isset($noHeader) && $noHeader)
        <x-public.header />
    @endunless

    <main class="flex-1 flex flex-col">
        @yield('body')
    </main>

    @unless(isset($noFooter) && $noFooter)
        <x-public.footer />
    @endunless

    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
