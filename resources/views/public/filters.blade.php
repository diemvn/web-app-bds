@extends('layouts.public')

@section('title', 'Bộ lọc')
@section('body_class', 'bg-surface')

@section('body')
<header class="stitch-fade-up sticky top-0 z-50 bg-surface dark:bg-background border-b border-outline-variant flex items-center justify-between px-margin-mobile h-16 w-full max-w-container-max mx-auto">
<div class="flex items-center gap-4">
<a href="{{ route('home') }}" class="stitch-filter-btn flex items-center justify-center w-10 h-10 rounded-full">
<span class="material-symbols-outlined text-primary">arrow_back</span>
</a>
<h1 class="text-headline-lg-mobile font-headline-lg-mobile text-primary">Bộ lọc</h1>
</div>
<a href="{{ route('filters') }}" class="text-label-md font-label-md text-primary font-bold">Xóa tất cả</a>
</header>

<form action="{{ route('home') }}" method="GET" class="stitch-page-enter max-w-md mx-auto px-margin-mobile pb-32 pt-6">
<section class="stitch-section mb-8" style="animation-delay: 0.05s">
<h2 class="text-title-lg font-title-lg mb-4 text-on-surface">Khu vực</h2>
<div class="relative mb-4">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input name="q" value="{{ request('q') }}" class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all duration-300 text-body-md font-body-md" placeholder="Nhập khu vực, quận huyện..." type="text">
</div>
<div class="flex flex-wrap gap-2" x-data="stitchFilterChips(@js(request('q', '')))">
@foreach(['Bình Thạnh', 'Quận 1', 'Thủ Đức', 'Gò Vấp'] as $district)
<button type="button" @click="select('{{ $district }}')" :class="chipClass('{{ $district }}')">{{ $district }}</button>
@endforeach
</div>
</section>

<section class="stitch-section mb-8" style="animation-delay: 0.12s">
<div class="flex justify-between items-center mb-6">
<h2 class="text-title-lg font-title-lg text-on-surface">Khoảng giá</h2>
<span class="text-primary font-bold text-body-md" id="price-label">
@if(request('price_min') || request('price_max'))
{{ request('price_min') ? (int)request('price_min')/1_000_000 : '1' }}tr - {{ request('price_max') ? (int)request('price_max')/1_000_000 : '20' }}tr+
@else
1tr - 20tr+
@endif
</span>
</div>
<div class="px-2 grid grid-cols-2 gap-3">
<input name="price_min" type="number" placeholder="Từ (triệu)" value="{{ request('price_min') ? (int)request('price_min')/1_000_000 : '' }}" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-body-md transition-all duration-300 focus:ring-2 focus:ring-primary focus:border-primary">
<input name="price_max" type="number" placeholder="Đến (triệu)" value="{{ request('price_max') ? (int)request('price_max')/1_000_000 : '' }}" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl text-body-md transition-all duration-300 focus:ring-2 focus:ring-primary focus:border-primary">
</div>
<p class="text-label-sm text-outline mt-2">Nhập đơn vị triệu (vd: 3 – 7)</p>
</section>

<section class="stitch-section mb-8" style="animation-delay: 0.19s">
<h2 class="text-title-lg font-title-lg mb-4 text-on-surface">Diện tích</h2>
<div class="flex gap-3 overflow-x-auto scrollbar-hide -mx-margin-mobile px-margin-mobile">
@foreach([['', 'Tất cả'], ['20', 'Dưới 20m²'], ['25', '20 - 30m²'], ['35', 'Trên 30m²']] as [$val, $label])
<label class="flex-shrink-0 cursor-pointer">
<input type="radio" name="area_min" value="{{ $val }}" class="peer sr-only" @checked(request('area_min', '') == $val)>
<span class="block px-6 py-2 border-2 transition-all duration-300 peer-checked:border-primary peer-checked:bg-primary-fixed peer-checked:text-primary peer-checked:font-bold border-outline-variant bg-surface-container-lowest text-on-surface-variant rounded-xl text-label-md font-label-md">{{ $label }}</span>
</label>
@endforeach
</div>
</section>

<section class="stitch-section mb-8" style="animation-delay: 0.26s">
<h2 class="text-title-lg font-title-lg mb-4 text-on-surface">Loại phòng</h2>
<div class="space-y-3">
@foreach(['Phòng trọ' => 'home_work', 'Chung cư mini' => 'apartment', 'Căn hộ' => 'domain'] as $type => $icon)
<label class="flex items-center justify-between p-4 bg-surface-container-lowest border border-outline-variant rounded-xl cursor-pointer transition-all duration-300 hover:bg-surface-container-low hover:border-outline">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-outline">{{ $icon }}</span>
<span class="text-body-md font-body-md">{{ $type }}</span>
</div>
<input class="w-6 h-6 rounded border-outline-variant text-primary focus:ring-primary" type="checkbox" @checked($loop->first)>
</label>
@endforeach
</div>
</section>

<div class="fixed bottom-0 left-0 w-full p-4 bg-surface dark:bg-background border-t border-outline-variant safe-bottom z-50 shadow-[0px_-8px_24px_rgba(0,0,0,0.05)] stitch-fade-up" style="animation-delay: 0.32s">
<div class="max-w-md mx-auto">
<button type="submit" class="stitch-cta-primary w-full py-4 font-bold text-body-lg rounded-2xl">
        Xem kết quả ({{ $count }} phòng)
      </button>
</div>
</div>
</form>
@endsection
