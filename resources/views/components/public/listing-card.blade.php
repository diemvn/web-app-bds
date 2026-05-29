@props(['listing'])

<a href="{{ route('listing.show', $listing->slug) }}" class="ml-card block overflow-hidden group">
    <div class="relative aspect-[4/3] bg-slate-100">
        @if($listing->thumbnail_url)
            <img src="{{ $listing->thumbnail_url }}" alt="" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300" loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-blue-50 to-slate-100">🏠</div>
        @endif
        <button type="button" class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 shadow flex items-center justify-center text-slate-400 hover:text-red-500" onclick="event.preventDefault()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>
    </div>
    <div class="p-4">
        <p class="text-lg font-bold text-[var(--ml-primary)]">{{ number_format($listing->price / 1000000, 1) }} triệu<span class="text-sm font-normal text-slate-500">/tháng</span></p>
        <h3 class="font-semibold text-slate-900 mt-1 line-clamp-2">{{ $listing->title }}</h3>
        <p class="text-sm text-slate-500 mt-1 flex items-center gap-1">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $listing->room?->building?->district ?? 'TP.HCM' }}
        </p>
        <div class="flex gap-3 mt-2 text-xs text-slate-400">
            @if($listing->area_m2)<span>{{ $listing->area_m2 }} m²</span>@endif
            <span>Wifi</span>
            <span>Giữ xe</span>
        </div>
    </div>
</a>
