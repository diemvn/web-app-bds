@extends('layouts.public')

@section('body_class', 'bg-background text-on-background font-body-md min-h-screen pb-32')

@push('json-ld')
@php
    $jsonLdImages = collect(\App\Models\Listing::normalizeImagePaths($listing->images))
        ->map(fn ($img) => \App\Support\SeoData::absoluteUrl(
            str_starts_with($img, 'http') ? $img : asset('storage/'.$img)
        ))
        ->filter()
        ->values()
        ->all();
    if (empty($jsonLdImages) && $listing->thumbnail_url) {
        $jsonLdImages = [\App\Support\SeoData::absoluteUrl($listing->thumbnail_url)];
    }
    $building = $listing->room?->building;
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Apartment',
        'name' => $listing->title,
        'description' => \Illuminate\Support\Str::limit(
            trim(preg_replace('/\s+/u', ' ', strip_tags($listing->description ?? ''))),
            500,
        ),
        'url' => route('listing.show', $listing->slug),
        'image' => $jsonLdImages,
        'floorSize' => [
            '@type' => 'QuantitativeValue',
            'value' => (float) $listing->area_m2,
            'unitCode' => 'MTK',
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => (float) $listing->price,
            'priceCurrency' => 'VND',
            'availability' => 'https://schema.org/InStock',
        ],
    ];
    if ($building?->address || $building?->district) {
        $jsonLd['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $building->address,
            'addressLocality' => $building->district,
            'addressRegion' => 'TP.HCM',
            'addressCountry' => 'VN',
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('body')
@php
    $images = collect(\App\Models\Listing::normalizeImagePaths($listing->images))
        ->map(fn ($img) => str_starts_with($img, 'http') ? $img : asset('storage/'.$img));
    if ($images->isEmpty() && $listing->thumbnail_url) {
        $images = collect([$listing->thumbnail_url]);
    }
    $imageCount = max($images->count(), 1);
    $priceFormatted = number_format($listing->price, 0, ',', '.');
    $address = trim(($listing->room?->building?->address ?? '').', '.($listing->room?->building?->district ?? 'TP.HCM'), ', ');
    $amenities = $listing->amenities ?? ['WiFi', 'Điều hoà', 'Gửi xe', 'An ninh', 'Giặt là', 'Bếp riêng', 'Thang máy', 'Hồ bơi'];
    $amenityIconMap = [
        'wifi' => 'wifi', 'điều' => 'ac_unit', 'máy lạnh' => 'ac_unit',
        'xe' => 'local_parking', 'an ninh' => 'security', 'giặt' => 'dry_cleaning',
        'bếp' => 'flatware', 'thang' => 'elevator', 'bơi' => 'pool',
    ];
    $iconFor = function (string $label) use ($amenityIconMap): string {
        $key = mb_strtolower($label);
        foreach ($amenityIconMap as $needle => $icon) {
            if (str_contains($key, $needle)) {
                return $icon;
            }
        }
        return 'check_circle';
    };
@endphp

<header class="stitch-fade-up fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-margin-mobile h-16 w-full max-w-container-max mx-auto transition-all bg-transparent" id="detail-header">
<a href="{{ route('home') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-lowest/80 text-on-surface shadow-sm active:scale-95 transition-transform">
<span class="material-symbols-outlined">arrow_back</span>
</a>
<div class="flex gap-2">
<button type="button" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-lowest/80 text-on-surface shadow-sm active:scale-95 transition-transform">
<span class="material-symbols-outlined">share</span>
</button>
<button type="button" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-lowest/80 text-on-surface shadow-sm active:scale-95 transition-transform" id="fav-btn">
<span class="material-symbols-outlined" id="fav-icon">favorite</span>
</button>
</div>
</header>

<main class="w-full max-w-container-max mx-auto">
<section
    id="gallery-viewport"
    class="stitch-detail-gallery relative z-[15] w-full h-[50vh] min-h-[280px] max-h-[420px] sm:h-[48vh] md:h-auto md:aspect-video md:min-h-0 md:max-h-none overflow-hidden"
>
<div
    id="gallery-track"
    class="flex h-full overflow-x-auto snap-x snap-mandatory hide-scrollbar touch-pan-x"
    role="region"
    aria-label="Ảnh phòng"
    data-slide-count="{{ $imageCount }}"
>
@forelse($images as $image)
<div class="snap-center shrink-0 w-full h-full" data-gallery-slide>
<img class="w-full h-full object-cover" alt="{{ $listing->title }}" src="{{ $image }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
</div>
@empty
<div class="snap-center shrink-0 w-full h-full flex items-center justify-center bg-surface-container text-7xl" data-gallery-slide>🏠</div>
@endforelse
</div>
@if($imageCount > 1)
<div class="pointer-events-none absolute inset-0 z-20 flex items-center justify-between px-2">
<button
    type="button"
    id="gallery-prev"
    class="gallery-nav-btn pointer-events-auto flex h-10 w-10 items-center justify-center rounded-full bg-black/40 text-white backdrop-blur-md active:scale-95 transition-transform"
    aria-label="Ảnh trước"
>
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button
    type="button"
    id="gallery-next"
    class="gallery-nav-btn pointer-events-auto flex h-10 w-10 items-center justify-center rounded-full bg-black/40 text-white backdrop-blur-md active:scale-95 transition-transform"
    aria-label="Ảnh sau"
>
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
<div class="pointer-events-none absolute inset-x-0 bottom-0 z-20 bg-gradient-to-t from-black/55 via-black/25 to-transparent pt-12 pb-4 px-margin-mobile">
<div class="pointer-events-auto flex items-center justify-between gap-3">
<div id="gallery-dots" class="flex flex-1 gap-1.5 overflow-x-auto hide-scrollbar py-1">
@for($i = 0; $i < $imageCount; $i++)
<button
    type="button"
    class="gallery-dot shrink-0 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white w-4 h-2' : 'bg-white/40 w-2 h-2' }}"
    data-index="{{ $i }}"
    aria-label="Ảnh {{ $i + 1 }}"
></button>
@endfor
</div>
<span class="shrink-0 bg-black/50 text-white px-3 py-1 rounded-lg text-label-sm font-label-sm backdrop-blur-sm" id="gallery-counter">1/{{ $imageCount }}</span>
</div>
</div>
@endif
</section>

<article class="px-margin-mobile -mt-10 relative z-10 bg-background rounded-t-3xl pt-3 text-left shadow-[0_-8px_24px_rgba(0,0,0,0.06)]">
<div class="stitch-section mb-sm" style="animation-delay: 0.05s">
<h1 class="text-on-surface font-title-lg text-title-lg mb-1 line-clamp-2">{{ $listing->title }}</h1>
<div class="flex items-baseline gap-2 mb-0.5">
<p class="text-primary font-headline-lg-mobile text-headline-lg-mobile m-0">{{ $priceFormatted }}đ</p>
<span class="text-on-surface-variant text-label-md font-label-md">/ tháng</span>
</div>
<p class="text-on-surface-variant flex items-center gap-1 text-label-md font-label-md line-clamp-1">
<span class="material-symbols-outlined text-[18px] shrink-0">location_on</span>
<span class="truncate">{{ $address }}</span>
</p>
</div>

<div class="stitch-section flex flex-wrap gap-x-6 gap-y-3 mb-0 pb-sm border-b border-outline-variant" style="animation-delay: 0.1s">
<div class="flex items-start gap-2 min-w-[7rem]">
<span class="material-symbols-outlined text-primary shrink-0">square_foot</span>
<div class="text-left">
<span class="block text-on-surface font-bold text-label-md">{{ $listing->area_m2 ?? $listing->room?->area_m2 ?? '—' }} m²</span>
<span class="block text-on-surface-variant text-label-sm">Diện tích</span>
</div>
</div>
<div class="flex items-start gap-2 min-w-[7rem]">
<span class="material-symbols-outlined text-primary shrink-0">layers</span>
<div class="text-left">
<span class="block text-on-surface font-bold text-label-md">Tầng {{ $listing->room?->floor ?? '—' }}</span>
<span class="block text-on-surface-variant text-label-sm">Vị trí</span>
</div>
</div>
<div class="flex items-start gap-2 min-w-[7rem]">
<span class="material-symbols-outlined text-primary shrink-0">explore</span>
<div class="text-left">
<span class="block text-on-surface font-bold text-label-md">—</span>
<span class="block text-on-surface-variant text-label-sm">Hướng</span>
</div>
</div>
</div>

<section class="stitch-section mb-lg pt-md" style="animation-delay: 0.15s">
<h3 class="text-on-surface font-title-lg text-title-lg mb-md">Tiện ích căn hộ</h3>
<div class="flex flex-wrap gap-x-4 gap-y-3 stitch-stagger">
@foreach(collect($amenities)->take(8) as $amenity)
@php $label = is_string($amenity) ? $amenity : (string) $amenity; @endphp
<div class="flex items-center gap-2 stitch-stagger-item text-left">
<div class="w-12 h-12 shrink-0 rounded-xl bg-surface-container flex items-center justify-center text-primary">
<span class="material-symbols-outlined">{{ $iconFor($label) }}</span>
</div>
<span class="text-label-sm font-label-sm text-on-surface-variant">{{ $label }}</span>
</div>
@endforeach
</div>
</section>

<section class="stitch-section mb-lg" style="animation-delay: 0.2s">
<h3 class="text-on-surface font-title-lg text-title-lg mb-sm">Mô tả chi tiết</h3>
<div class="text-left text-on-surface-variant text-body-md font-body-md leading-relaxed prose prose-sm max-w-none prose-p:text-left">
{!! $listing->description ?: '<p>Phòng đẹp, thoáng mát, đầy đủ tiện nghi.</p>' !!}
</div>
</section>

@if($listing->lat && $listing->lng)
<section class="stitch-section mb-lg" style="animation-delay: 0.25s">
<div class="flex justify-between items-center mb-md">
<h3 class="text-on-surface font-title-lg text-title-lg">Vị trí</h3>
<a href="https://www.google.com/maps/dir/?api=1&destination={{ $listing->lat }},{{ $listing->lng }}" target="_blank" rel="noopener" class="text-primary font-bold text-label-md">Chỉ đường</a>
</div>
<div id="mini-map" class="w-full h-48 rounded-2xl overflow-hidden relative shadow-sm border border-outline-variant" data-lat="{{ $listing->lat }}" data-lng="{{ $listing->lng }}"></div>
</section>
@endif
</article>
</main>

<nav class="stitch-fade-up fixed inset-x-0 bottom-0 z-50 bg-surface dark:bg-background shadow-[0px_-8px_24px_rgba(0,0,0,0.08)] border-t border-outline-variant/40" style="animation-delay: 0.28s">
<div class="mx-auto flex h-20 w-full max-w-container-max items-center gap-3 px-margin-mobile pt-3 pb-safe">
<a href="tel:{{ $branding['contact_phone'] ?? '0901000000' }}" class="flex min-h-[52px] min-w-0 flex-1 items-center justify-center gap-2 rounded-xl border-2 border-primary py-3.5 font-bold text-primary active:scale-95 transition-all">
<span class="material-symbols-outlined shrink-0">call</span>
<span class="truncate">Gọi điện</span>
</a>
<a href="{{ route('listing.contact', $listing->slug) }}" class="stitch-cta-primary flex min-h-[52px] min-w-0 flex-[1.5] items-center justify-center gap-2 rounded-xl py-3.5 font-bold">
<span class="material-symbols-outlined shrink-0">calendar_month</span>
<span class="truncate">Đặt lịch xem</span>
</a>
</div>
</nav>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@vite('resources/js/listing-gallery.js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof window.initListingMiniMap === 'function') {
            window.initListingMiniMap();
        }
    });
</script>
@endpush
