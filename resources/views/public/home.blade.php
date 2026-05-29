@extends('layouts.public')

@section('body_class', 'bg-background text-on-background font-body-md min-h-screen pb-32 md:pb-8')

@section('body')
<div class="stitch-home-shell mx-auto w-full">
<h1 class="sr-only">{{ $branding['app_name'] ?? 'HOSTY' }} — {{ $branding['tagline'] ?? 'Tìm phòng trọ' }}</h1>
<header class="sticky top-0 z-50 flex items-center justify-between px-margin-mobile h-16 w-full max-w-[780px] mx-auto bg-surface dark:bg-background border-b border-outline-variant">
<div class="flex items-center gap-3 w-full">
<form action="{{ route('home') }}" method="GET" class="flex items-center flex-1 gap-2 w-full" @submit.prevent="$store.filterSheet.quickSearch()">
<div class="flex items-center flex-1 bg-surface-container-low dark:bg-surface-container-highest rounded-full px-4 py-2 transition-colors">
<span class="material-symbols-outlined text-outline" data-icon="search">search</span>
<input name="q" value="{{ request('q') }}" class="bg-transparent border-none focus:ring-0 text-label-md font-label-md text-on-surface-variant w-full ml-2" placeholder="Tìm khu vực, quận huyện..." type="search">
</div>
<a href="{{ route('map.index') }}" class="stitch-filter-btn hidden md:flex items-center justify-center h-10 px-3 rounded-full bg-primary-container text-on-primary-container text-label-sm font-label-sm font-semibold whitespace-nowrap">
<span class="material-symbols-outlined text-sm mr-1">map</span>Bản đồ
</a>
<button type="button" data-filter-open @click="$store.filterSheet.show()" class="stitch-filter-btn flex items-center justify-center h-10 w-10 rounded-full bg-surface-container-low dark:bg-surface-container-highest" aria-label="Mở bộ lọc">
<span class="material-symbols-outlined text-primary dark:text-primary-fixed" data-icon="tune">tune</span>
</button>
</form>
</div>
</header>

<main class="stitch-page-enter max-w-[780px] mx-auto w-full">
<div class="flex overflow-x-auto hide-scrollbar gap-sm px-margin-mobile py-4 bg-surface/80 backdrop-blur-sm sticky top-16 z-40" x-data="stitchChips">
<button type="button" @click="pick(0)" :class="chipClass(0)">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">local_fire_department</span>
                Giá tốt
            </button>
<button type="button" @click="pick(1)" :class="chipClass(1)">
<span class="material-symbols-outlined text-sm">new_releases</span>
                Mới nhất
            </button>
<button type="button" @click="pick(2)" :class="chipClass(2)">
<span class="material-symbols-outlined text-sm">history</span>
                Gần đây
            </button>
<button type="button" data-filter-open @click="pick(3); $store.filterSheet.show()" :class="chipClass(3)">
<span class="material-symbols-outlined text-sm">straighten</span>
                Diện tích
            </button>
</div>

<div id="listings-feed" class="stitch-stagger stitch-home-feed px-margin-mobile flex flex-col gap-gutter mt-2 pb-4">
@include('public.partials.listings-feed', ['listings' => $listings])
</div>
</main>
</div>

<div
    x-data="stitchFab"
    x-show="!hidden"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 translate-y-8"
    class="fixed bottom-24 left-1/2 -translate-x-1/2 z-40 md:hidden"
>
<a href="{{ route('map.index') }}" class="glass-fab text-white px-6 py-3 rounded-full flex items-center gap-2 shadow-lg">
<span class="material-symbols-outlined" data-icon="map">map</span>
<span class="font-bold text-label-md">Xem bản đồ</span>
</a>
</div>

<div class="md:hidden">
<x-stitch.bottom-nav active="home" />
</div>

<x-public.filter-sheet />
@endsection
