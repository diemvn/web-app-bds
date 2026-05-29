@props(['listing', 'badge' => null])

@php
    $priceMillion = number_format($listing->price / 1_000_000, 1, ',', '.');
    $address = trim(($listing->room?->building?->address ?? '').', '.($listing->room?->building?->district ?? 'TP.HCM'), ', ');
    $amenities = collect($listing->amenities ?? ['Wifi', 'Điều hòa', 'Để xe'])->take(3);
    $amenityIcons = [
        'wifi' => 'wifi', 'điều hòa' => 'ac_unit', 'máy lạnh' => 'ac_unit',
        'xe' => 'directions_bike', 'ban công' => 'balcony', 'an ninh' => 'security',
    ];
    $iconFor = function (string $label) use ($amenityIcons): string {
        $key = mb_strtolower($label);
        foreach ($amenityIcons as $needle => $icon) {
            if (str_contains($key, $needle)) {
                return $icon;
            }
        }
        return 'check_circle';
    };
@endphp

<a href="{{ route('listing.show', $listing->slug) }}" class="block stitch-stagger-item">
<article class="stitch-card bg-surface-container-lowest rounded-xl overflow-hidden property-card-shadow group">
<div class="aspect-[16/9] relative overflow-hidden">
@if($listing->thumbnail_url)
<img class="stitch-card-image w-full h-full object-cover" alt="{{ $listing->title }}" src="{{ $listing->thumbnail_url }}">
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-5xl">🏠</div>
@endif
@if($badge === 'verified')
<div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2 py-1 rounded-lg flex items-center gap-1">
<span class="material-symbols-outlined text-primary text-[16px]" data-icon="verified" style="font-variation-settings: 'FILL' 1;">verified</span>
<span class="text-[11px] font-bold text-primary">CHÍNH CHỦ</span>
</div>
@elseif($badge === 'promo')
<div class="absolute top-3 left-3 bg-secondary-container text-white px-2 py-1 rounded-lg flex items-center gap-1">
<span class="text-[11px] font-bold uppercase tracking-wider">Ưu đãi tháng này</span>
</div>
@endif
<button type="button" class="absolute top-3 right-3 h-10 w-10 flex items-center justify-center bg-white/20 backdrop-blur-lg rounded-full text-white" onclick="event.preventDefault(); event.stopPropagation();">
<span class="material-symbols-outlined" data-icon="favorite">favorite</span>
</button>
</div>
<div class="p-md">
<div class="flex justify-between items-start mb-2">
<h3 class="text-on-background font-headline-md text-headline-md line-clamp-1 flex-1">{{ $listing->title }}</h3>
<span class="text-primary font-bold text-lg ml-2 whitespace-nowrap">{{ $priceMillion }} triệu/tháng</span>
</div>
<div class="flex items-center gap-1 text-on-surface-variant text-label-md mb-3">
<span class="material-symbols-outlined text-sm" data-icon="location_on">location_on</span>
<span class="line-clamp-1">{{ $address }}</span>
</div>
<div class="flex items-center gap-4 py-3 border-t border-outline-variant/30">
@if($listing->area_m2)
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-outline text-lg" data-icon="square_foot">square_foot</span>
<span class="text-label-md text-on-surface-variant">{{ $listing->area_m2 }} m2</span>
</div>
@endif
@foreach($amenities as $amenity)
@php $label = is_string($amenity) ? $amenity : (string) $amenity; @endphp
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-outline text-lg" data-icon="{{ $iconFor($label) }}">{{ $iconFor($label) }}</span>
<span class="text-label-md text-on-surface-variant">{{ $label }}</span>
</div>
@endforeach
</div>
</div>
</article>
</a>
