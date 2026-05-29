@extends('layouts.stitch-map')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

@section('body')
{{-- Mobile: chuyển về danh sách --}}
<div class="md:hidden flex flex-col h-full">
    <header class="flex-none bg-surface-container-lowest border-b border-outline-variant px-md h-14 flex items-center gap-3">
        <a href="{{ route('home') }}" class="material-symbols-outlined text-primary">arrow_back</a>
        <span class="font-bold text-primary">Bản đồ</span>
    </header>
    <div id="map-mobile" class="flex-1 min-h-[40vh]"></div>
    <aside id="listing-panel-mobile" class="flex-1 overflow-y-auto p-md border-t border-outline-variant"></aside>
</div>

{{-- Desktop: Zillow split (Stitch) --}}
<div class="stitch-map-desktop hidden md:flex md:flex-col md:flex-1 md:overflow-hidden md:h-full">
<header class="flex-none bg-surface-container-lowest border-b border-outline-variant px-lg h-16 flex items-center justify-between z-50">
<div class="flex items-center gap-xl">
<a href="{{ route('home') }}" class="text-xl font-bold text-primary tracking-tight">{{ $branding['app_name'] ?? 'Modern Living' }}</a>
<nav class="hidden lg:flex items-center gap-md"></nav>
</div>
<div class="flex items-center gap-md">
<button type="button" class="text-on-surface hover:text-primary transition-colors flex items-center p-2">
<span class="material-symbols-outlined">favorite</span>
</button>
<button type="button" class="hidden md:block font-medium text-on-surface hover:text-primary">Trợ giúp</button>
<a href="{{ route('tenant.login') }}" class="font-medium text-on-surface hover:text-primary px-2 transition-colors">Đăng nhập</a>
</div>
</header>

<div class="flex-none bg-surface-container-lowest border-b border-outline-variant px-lg py-sm flex flex-wrap items-center gap-sm z-40">
<div class="relative flex-1 max-w-md">
<input id="map-q" class="w-full pl-md pr-10 py-2 border border-outline-variant rounded focus:ring-1 focus:ring-primary focus:border-primary outline-none" placeholder="Địa chỉ, khu phố, thành phố, mã vùng..." type="text">
<span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline pointer-events-none">search</span>
</div>
<div class="flex items-center gap-sm">
<button type="button" class="stitch-filter-btn flex items-center gap-xs px-md py-2 border border-outline-variant rounded">
<span class="font-medium text-sm">Cho thuê</span>
<span class="material-symbols-outlined text-sm">expand_more</span>
</button>
<button type="button" id="map-price-btn" class="stitch-filter-btn flex items-center gap-xs px-md py-2 border border-outline-variant rounded">
<span class="font-medium text-sm">Giá</span>
<span class="material-symbols-outlined text-sm">expand_more</span>
</button>
<select id="map-price" class="sr-only" aria-hidden="true">
<option value="">Tất cả giá</option>
<option value="5000000">≤ 5 tr</option>
<option value="7000000">≤ 7 tr</option>
<option value="10000000">≤ 10 tr</option>
<option value="15000000">≤ 15 tr</option>
</select>
<button type="button" class="stitch-filter-btn flex items-center gap-xs px-md py-2 border border-outline-variant rounded">
<span class="font-medium text-sm">Giường &amp; Tắm</span>
<span class="material-symbols-outlined text-sm">expand_more</span>
</button>
<button type="button" class="stitch-filter-btn flex items-center gap-xs px-md py-2 border border-outline-variant rounded">
<span class="font-medium text-sm">Loại nhà</span>
<span class="material-symbols-outlined text-sm">expand_more</span>
</button>
<a href="{{ route('home', ['filters' => 'open']) }}" class="stitch-filter-btn flex items-center gap-xs px-md py-2 border border-outline-variant rounded">
<span class="material-symbols-outlined text-sm">tune</span>
<span class="font-medium text-sm">Bộ lọc</span>
</a>
</div>
<div class="ml-auto">
<button type="button" class="bg-primary text-on-primary px-lg py-2 rounded font-semibold hover:bg-opacity-90">Lưu tìm kiếm</button>
</div>
</div>

<main class="flex-1 flex overflow-hidden">
<section class="relative w-[65%] h-full bg-surface-variant map-gradient border-r border-outline-variant">
<div id="map" class="absolute inset-0 z-[1]"></div>
<div class="absolute top-md left-1/2 -translate-x-1/2 z-10">
<button type="button" onclick="window.loadListings?.()" class="bg-surface-container-lowest shadow-lg border border-outline-variant px-lg py-2 rounded-full flex items-center gap-sm hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-primary" style="font-size: 20px;">refresh</span>
<span class="font-semibold text-sm">Tìm kiếm trong khu vực này</span>
</button>
</div>
<div class="absolute bottom-md right-md z-10 flex flex-col gap-2">
<div class="flex flex-col bg-surface-container-lowest shadow-lg rounded border border-outline-variant overflow-hidden">
<button type="button" id="map-zoom-in" class="p-2 hover:bg-surface-container border-b border-outline-variant">
<span class="material-symbols-outlined">add</span>
</button>
<button type="button" id="map-zoom-out" class="p-2 hover:bg-surface-container">
<span class="material-symbols-outlined">remove</span>
</button>
</div>
<button type="button" id="map-my-location" class="bg-surface-container-lowest p-2 shadow-lg rounded border border-outline-variant hover:bg-surface-container">
<span class="material-symbols-outlined">my_location</span>
</button>
</div>
</section>

<section class="w-[35%] h-full bg-surface-container-lowest flex flex-col overflow-hidden">
<div class="p-lg border-b border-outline-variant flex flex-col gap-1 bg-surface-container-lowest sticky top-0 z-20">
<h1 class="text-xl font-bold text-on-surface">Bản đồ toàn cảnh: Ho Chi Minh Real Estate</h1>
<div class="flex items-center justify-between mt-1">
<span class="text-on-surface-variant text-sm font-medium" id="map-result-count">{{ $total }} kết quả</span>
<div class="flex items-center gap-xs cursor-pointer hover:text-primary group">
<span class="text-sm font-semibold">Sắp xếp: Phù hợp nhất</span>
<span class="material-symbols-outlined text-sm group-hover:translate-y-0.5 transition-transform">expand_more</span>
</div>
</div>
</div>

<div id="listing-panel" class="stitch-map-panel flex-1 overflow-y-auto p-md hide-scrollbar">
<div class="grid grid-cols-1 @xl:grid-cols-2 gap-md">
@forelse($listings as $listing)
<x-stitch.listing-card-desktop :listing="$listing" :showcase="$loop->first || $loop->iteration === 3" />
@empty
<p class="text-on-surface-variant text-sm text-center py-8 col-span-full">Không có phòng trong khu vực này.</p>
@endforelse
</div>
</div>

<div class="p-md bg-surface-container-lowest border-t border-outline-variant shadow-lg z-30">
<a href="{{ route('home') }}" class="stitch-cta-primary w-full py-3 rounded-lg font-bold flex justify-center items-center gap-sm">
<span class="material-symbols-outlined">calendar_month</span>
                Đặt lịch xem phòng nhanh
            </a>
</div>
</section>
</main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
@endpush
