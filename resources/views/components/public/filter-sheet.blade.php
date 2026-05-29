@php
    $initial = [
        'q' => request('q', ''),
        'price_min' => request('price_min') ? (int) request('price_min') / 1_000_000 : '',
        'price_max' => request('price_max') ? (int) request('price_max') / 1_000_000 : '',
        'area_min' => request('area_min', ''),
        'openOnLoad' => request('filters') === 'open',
    ];
@endphp

<div
    x-data="stitchFilterSheet(@js($initial))"
    data-filter-sheet-root
    @keydown.escape.window="$store.filterSheet.close()"
    class="contents"
>
    <div
        x-show="$store.filterSheet.open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.filterSheet.close()"
        class="fixed inset-0 z-[60] bg-black/40"
        aria-hidden="true"
    ></div>

    <div
        x-show="$store.filterSheet.open"
        x-cloak
        x-transition:enter="transition ease-out duration-350"
        x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-full opacity-0"
        class="fixed inset-x-0 top-0 z-[70] mx-auto w-full max-w-[780px] max-h-[min(92vh,900px)] flex flex-col bg-surface rounded-b-2xl shadow-[0px_12px_40px_rgba(0,0,0,0.15)] overflow-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="filter-sheet-title"
        @click.stop
    >
        <header class="shrink-0 flex items-center justify-between px-margin-mobile h-16 border-b border-outline-variant bg-surface">
            <div class="flex items-center gap-3">
                <button type="button" @click="$store.filterSheet.close()" class="stitch-filter-btn flex items-center justify-center w-10 h-10 rounded-full">
                    <span class="material-symbols-outlined text-primary">close</span>
                </button>
                <h2 id="filter-sheet-title" class="text-headline-lg-mobile font-headline-lg-mobile text-primary">Bộ lọc</h2>
            </div>
            <button type="button" @click="reset()" class="text-label-md font-label-md text-primary font-bold">Xóa tất cả</button>
        </header>

        <div class="flex-1 overflow-y-auto hide-scrollbar px-margin-mobile py-6 space-y-8">
            <section class="stitch-section" style="animation-delay: 0.05s">
                <h3 class="text-title-lg font-title-lg mb-4 text-on-surface">Khu vực</h3>
                <div class="relative mb-4">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input x-model="q" type="text" class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md font-body-md" placeholder="Nhập khu vực, quận huyện...">
                </div>
                <div class="flex flex-wrap gap-2">
                    <template x-for="district in districts" :key="district">
                        <button type="button" @click="q = district" :class="districtChipClass(district)" x-text="district"></button>
                    </template>
                </div>
            </section>

            <section class="stitch-section" style="animation-delay: 0.12s">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-title-lg font-title-lg text-on-surface">Khoảng giá</h3>
                    <span class="text-primary font-bold text-body-md" x-text="priceLabel"></span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input x-model="priceMin" type="number" min="0" placeholder="Từ (triệu)" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <input x-model="priceMax" type="number" min="0" placeholder="Đến (triệu)" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
                <p class="text-label-sm text-outline mt-2">Nhập đơn vị triệu (vd: 3 – 7)</p>
            </section>

            <section class="stitch-section" style="animation-delay: 0.19s">
                <h3 class="text-title-lg font-title-lg mb-4 text-on-surface">Diện tích</h3>
                <div class="flex gap-3 overflow-x-auto hide-scrollbar -mx-margin-mobile px-margin-mobile pb-1">
                    <template x-for="opt in areaOptions" :key="opt.value">
                        <button type="button" @click="areaMin = opt.value" :class="areaChipClass(opt.value)" x-text="opt.label"></button>
                    </template>
                </div>
            </section>
        </div>

        <div class="shrink-0 p-4 border-t border-outline-variant bg-surface safe-bottom">
            <button
                type="button"
                @click="apply()"
                :disabled="loading"
                class="stitch-cta-primary w-full py-4 font-bold text-body-lg rounded-2xl flex items-center justify-center gap-2 disabled:opacity-70"
            >
                <span x-show="loading" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
                <span x-text="loading ? 'Đang lọc...' : `Xem kết quả (${resultCount} phòng)`"></span>
            </button>
        </div>
    </div>
</div>
