@props(['listing', 'showcase' => false])

@php
    $priceMillion = rtrim(rtrim(number_format($listing->price / 1_000_000, 1, '.', ''), '0'), '.');
    $address = trim(($listing->room?->building?->address ?? '').', '.($listing->room?->building?->district ?? ''), ', ');
    $area = $listing->area_m2 ?? $listing->room?->area_m2;
    $bedrooms = 1;
    $bathrooms = 1;
@endphp

<a href="{{ route('listing.show', $listing->slug) }}" class="stitch-card stitch-stagger-item group border border-outline-variant rounded-lg overflow-hidden bg-surface-container-lowest cursor-pointer block">
<div class="relative aspect-video overflow-hidden">
@if($listing->thumbnail_url)
<img alt="" class="stitch-card-image w-full h-full object-cover" src="{{ $listing->thumbnail_url }}">
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-4xl">🏠</div>
@endif
<button type="button" class="absolute top-2 right-2 text-white hover:text-red-500 drop-shadow-md" onclick="event.preventDefault(); event.stopPropagation();">
<span class="material-symbols-outlined text-2xl">favorite</span>
</button>
@if($showcase)
<div class="absolute bottom-2 left-2 bg-primary text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase">Showcase</div>
@endif
</div>
<div class="p-sm flex flex-col gap-1">
<div class="flex items-center justify-between">
<span class="text-xl font-bold">{{ $priceMillion }}tr <span class="text-sm font-normal">/tháng</span></span>
<span class="material-symbols-outlined text-outline text-lg">more_horiz</span>
</div>
<div class="text-sm font-medium flex gap-2">
<span>{{ $bedrooms }} p.ngủ</span>
<span class="text-outline">|</span>
<span>{{ $bathrooms }} p.tắm</span>
@if($area)
<span class="text-outline">|</span>
<span>{{ $area }} m²</span>
@endif
</div>
<div class="text-xs text-on-surface-variant truncate">{{ $address }}</div>
<div class="text-[10px] text-outline mt-1 uppercase font-bold tracking-tight">Niêm yết bởi: {{ $branding['app_name'] ?? 'Modern Living' }}</div>
</div>
</a>
