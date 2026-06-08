@forelse($listings as $listing)
    <div class="block md:hidden">
        <x-stitch.listing-card :listing="$listing" layout="horizontal" />
    </div>
    <div class="hidden md:block h-full">
        <x-stitch.listing-card :listing="$listing" />
    </div>
@empty
    <div class="col-span-1 md:col-span-2 py-12 text-center text-on-surface-variant bg-surface-container-lowest rounded-2xl border border-outline-variant border-dashed">
        <span class="material-symbols-outlined text-[48px] opacity-50 mb-4">search_off</span>
        <p>Không tìm thấy phòng nào trong khu vực này.</p>
        <button type="button" @click="clearFilters" class="mt-4 px-4 py-2 rounded-full border border-outline-variant text-sm font-medium hover:bg-surface-container-low transition-colors tactile-feedback">Xóa bộ lọc</button>
    </div>
@endforelse
