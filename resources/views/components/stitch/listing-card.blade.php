@props(['listing', 'badge' => null, 'layout' => 'vertical'])

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
    
    // Simulate bedrooms based on area for the mockup
    $bedrooms = $listing->area_m2 > 30 ? 2 : 1; 
@endphp

@if($layout === 'horizontal')
<article class="stitch-card group flex flex-row bg-surface rounded-2xl overflow-hidden shadow-sm border border-outline-variant/30 h-[120px] tactile-feedback cursor-pointer hover:bg-surface-container-lowest transition-colors">
    <a href="{{ route('listing.show', $listing->slug ?? 'slug') }}" class="block relative w-[120px] flex-none bg-surface-container">
        @if($listing->thumbnail_url)
            <img src="{{ $listing->thumbnail_url }}" 
                 alt="{{ $listing->title }}" 
                 class="w-full h-full object-cover" 
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-on-surface-variant bg-surface-variant">
                <span class="material-symbols-outlined text-[32px] opacity-50">image</span>
            </div>
        @endif
        
        @if($badge === 'verified')
            <div class="absolute top-2 left-2 bg-surface/90 backdrop-blur-md px-1.5 py-0.5 rounded flex items-center shadow-sm z-10">
                <span class="material-symbols-outlined text-primary text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span>
            </div>
        @endif
    </a>
    
    <div class="flex flex-col flex-1 p-3 min-w-0 justify-between">
        <div>
            <h3 class="font-bold text-on-surface text-sm line-clamp-1 group-hover:text-primary transition-colors">
                <a href="{{ route('listing.show', $listing->slug ?? 'slug') }}">{{ $listing->title }}</a>
            </h3>
            
            <div class="text-xs text-on-surface-variant mt-1">
                {{ $listing->area_m2 }} m² | {{ $bedrooms }} p.ngủ
            </div>
            
            <div class="flex items-center gap-1 text-xs text-on-surface-variant mt-1 truncate">
                <span class="material-symbols-outlined text-[14px]">location_on</span>
                <span class="truncate">{{ $listing->room?->building?->district ?? 'TP.HCM' }}, HCM</span>
            </div>
        </div>
        
        <div class="font-bold text-primary text-sm mt-1">
            {{ $priceMillion }}tr/tháng
        </div>
    </div>
</article>
@else
<article class="stitch-card group flex flex-col bg-surface-container-lowest rounded-[20px] overflow-hidden property-card-shadow h-full">
    <a href="{{ route('listing.show', $listing->slug ?? 'slug') }}" class="block relative aspect-[4/3] overflow-hidden bg-surface-container">
        @if($listing->thumbnail_url)
            <img src="{{ $listing->thumbnail_url }}" 
                 alt="{{ $listing->title }}" 
                 class="stitch-card-image w-full h-full object-cover" 
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-on-surface-variant bg-surface-variant">
                <span class="material-symbols-outlined text-[48px] opacity-50">image</span>
            </div>
        @endif
        
        <!-- Badges -->
        @if($badge === 'verified')
            <div class="absolute top-3 left-3 bg-surface/90 backdrop-blur-md px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-sm z-10">
                <span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span class="text-[10px] font-bold text-primary uppercase tracking-wider">CHÍNH CHỦ</span>
            </div>
        @elseif($badge === 'promo')
            <div class="absolute top-3 left-3 bg-secondary-container text-on-secondary-container px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-sm z-10">
                <span class="text-[10px] font-bold uppercase tracking-wider">Ưu đãi</span>
            </div>
        @endif
        
        <!-- Favorite Button (Alpine component for guest/auth toggle) -->
        <button type="button" 
                x-data="{ isFavorite: false }" 
                x-init="
                    $nextTick(() => {
                        isFavorite = $store.favorites ? $store.favorites.has({{ $listing->id }}) : false;
                    });
                "
                @click.prevent="
                    if ($store.favorites) {
                        $store.favorites.toggle({{ $listing->id }});
                        isFavorite = $store.favorites.has({{ $listing->id }});
                    }
                "
                class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center bg-surface/80 backdrop-blur-md hover:bg-surface rounded-full text-on-surface transition-all shadow-sm z-10 tactile-feedback"
                aria-label="Lưu phòng">
            <span class="material-symbols-outlined text-[20px] transition-colors" 
                  :class="isFavorite ? 'text-error' : 'text-on-surface-variant'" 
                  :style="isFavorite ? 'font-variation-settings: \'FILL\' 1;' : ''">
                favorite
            </span>
        </button>
    </a>
    
    <div class="flex flex-col flex-1 p-4 md:p-5">
        <div class="flex justify-between items-start gap-3 mb-2">
            <h3 class="title-md text-on-surface group-hover:text-primary transition-colors line-clamp-2">
                <a href="{{ route('listing.show', $listing->slug ?? 'slug') }}">{{ $listing->title }}</a>
            </h3>
        </div>
        
        <div class="flex items-center gap-1.5 text-sm text-on-surface-variant mb-4">
            <span class="material-symbols-outlined text-[16px]">location_on</span>
            <span class="line-clamp-1">{{ $address }}</span>
        </div>
        
        <div class="mt-auto">
            <div class="flex items-center gap-x-4 gap-y-2 flex-wrap pb-4">
                @if($listing->area_m2)
                    <div class="flex items-center gap-1.5 bg-surface-container-low px-2 py-1 rounded-md">
                        <span class="material-symbols-outlined text-outline text-[16px]">square_foot</span>
                        <span class="text-xs font-medium text-on-surface-variant">{{ $listing->area_m2 }}m²</span>
                    </div>
                @endif
                @foreach($amenities as $amenity)
                    @php $label = is_string($amenity) ? $amenity : (string) $amenity; @endphp
                    <div class="flex items-center gap-1.5 bg-surface-container-low px-2 py-1 rounded-md">
                        <span class="material-symbols-outlined text-outline text-[16px]">{{ $iconFor($label) }}</span>
                        <span class="text-xs font-medium text-on-surface-variant">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Giá thuê</span>
                    <span class="text-primary font-bold text-lg md:text-xl">{{ $priceMillion }}<span class="text-sm font-normal text-on-surface-variant ml-1">tr/tháng</span></span>
                </div>
                <a href="{{ route('listing.show', $listing->slug ?? 'slug') }}" class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center transition-colors hover:bg-primary hover:text-on-primary tactile-feedback">
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</article>
@endif
