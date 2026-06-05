@extends('layouts.public')

@section('title', 'Trang chủ - '.($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0')

@section('body')
<!-- Hero Section -->
<section class="relative bg-surface-container-lowest overflow-hidden pb-12 pt-8 md:pt-16 md:pb-24 border-b border-outline-variant/30">
    <div class="taste-container taste-section relative z-10 flex flex-col items-center text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-on-surface mb-6 stitch-fade-up">
            Tìm phòng trọ lý tưởng<br>
            <span class="text-primary">dễ dàng và nhanh chóng</span>
        </h1>
        <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl mb-10 stitch-fade-up" style="animation-delay: 0.1s;">
            Hàng ngàn phòng trọ, căn hộ dịch vụ chất lượng đang chờ đón bạn. Khám phá không gian sống mới cùng {{ $branding['app_name'] ?? 'HOSTY' }}.
        </p>

        <!-- Search Bar -->
        <div class="w-full max-w-3xl bg-surface p-2 md:p-3 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-outline-variant/50 flex items-center gap-2 stitch-fade-up" style="animation-delay: 0.2s;">
            <form action="{{ route('map.index') }}" method="GET" class="flex-1 flex items-center h-12 md:h-14">
                <div class="flex-1 flex items-center px-4 gap-3 md:border-r md:border-outline-variant/50">
                    <span class="material-symbols-outlined text-on-surface-variant">search</span>
                    <input type="text" name="q" placeholder="Nhập địa điểm, quận, huyện..." 
                           class="w-full bg-transparent border-none focus:ring-0 text-base text-on-surface placeholder:text-on-surface-variant/70 p-0 outline-none">
                </div>
                <div class="hidden md:flex items-center px-4 gap-3">
                    <span class="material-symbols-outlined text-on-surface-variant">tune</span>
                    <select name="price" class="bg-transparent border-none focus:ring-0 text-sm text-on-surface font-medium cursor-pointer outline-none w-32">
                        <option value="">Mức giá</option>
                        <option value="under_3">Dưới 3 triệu</option>
                        <option value="3_5">3 - 5 triệu</option>
                        <option value="over_5">Trên 5 triệu</option>
                    </select>
                </div>
                <button type="submit" class="bg-primary text-on-primary h-full px-6 md:px-8 rounded-full font-bold text-sm md:text-base transition-colors hover:bg-primary-fixed-variant tactile-feedback ml-2">
                    Tìm kiếm
                </button>
            </form>
        </div>

        <!-- Quick Filters -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-8 stitch-fade-up" style="animation-delay: 0.3s;">
            <span class="text-sm font-medium text-on-surface-variant mr-2 hidden md:inline-block">Gợi ý:</span>
            <a href="{{ route('map.index', ['q' => 'Quận 1']) }}" class="px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">Quận 1</a>
            <a href="{{ route('map.index', ['q' => 'Quận 7']) }}" class="px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">Quận 7</a>
            <a href="{{ route('map.index', ['q' => 'Bình Thạnh']) }}" class="px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">Bình Thạnh</a>
        </div>
    </div>
    
    <!-- Decorative background elements -->
    <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-primary-container rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
    <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-tertiary-container rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
</section>

<!-- Featured Listings -->
<section class="taste-section taste-container py-16 md:py-24">
    <div class="flex items-end justify-between mb-8 md:mb-12">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-on-surface mb-2">Phòng nổi bật</h2>
            <p class="text-on-surface-variant">Những không gian sống được yêu thích nhất</p>
        </div>
        <a href="{{ route('map.index') }}" class="hidden md:flex items-center gap-1.5 text-primary font-bold hover:text-primary-fixed-variant transition-colors group">
            Xem tất cả
            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($listings->take(8) as $listing)
            <x-stitch.listing-card :listing="$listing" :badge="$loop->first ? 'promo' : null" />
        @empty
            <div class="col-span-full py-12 text-center bg-surface-container-lowest rounded-2xl border border-outline-variant border-dashed">
                <span class="material-symbols-outlined text-[48px] text-outline-variant mb-4">search_off</span>
                <p class="text-on-surface-variant">Chưa có phòng nào được đăng</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 text-center md:hidden">
        <a href="{{ route('map.index') }}" class="inline-flex items-center justify-center w-full px-6 py-3.5 rounded-full border border-outline text-on-surface font-semibold hover:bg-surface-container-low transition-colors">
            Xem tất cả phòng
        </a>
    </div>
</section>

<!-- Latest News -->
<section class="taste-section bg-surface-container-lowest border-t border-outline-variant/30 py-16 md:py-24">
    <div class="taste-container">
        <div class="flex items-end justify-between mb-8 md:mb-12">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-on-surface mb-2">Tin tức & Cẩm nang</h2>
                <p class="text-on-surface-variant">Kinh nghiệm thuê phòng và mẹo vặt cuộc sống</p>
            </div>
            <a href="{{ route('news.index') ?? '/tin-tuc' }}" class="hidden md:flex items-center gap-1.5 text-primary font-bold hover:text-primary-fixed-variant transition-colors group">
                Đọc thêm
                <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                try {
                    $articles = \App\Models\Article::where('is_published', true)->latest('published_at')->take(3)->get();
                } catch (\Exception $e) {
                    $articles = collect([]);
                }
            @endphp
            @if($articles->count() > 0)
                @foreach($articles as $article)
                    <x-public.news-card :article="$article" />
                @endforeach
            @else
                <!-- Mock for empty state -->
                @for($i = 0; $i < 3; $i++)
                    <div class="p-6 bg-surface border border-outline-variant border-dashed rounded-2xl flex flex-col items-center justify-center text-center opacity-50 h-64">
                        <span class="material-symbols-outlined text-[32px] mb-2">article</span>
                        <p class="text-sm font-medium">Bài viết đang cập nhật</p>
                    </div>
                @endfor
            @endif
        </div>
    </div>
</section>

<x-public.cta-section 
    headline="Tìm kiếm phòng trọ lý tưởng ngay hôm nay?"
    subtext="Khám phá hàng ngàn phòng trọ, căn hộ chất lượng được cập nhật liên tục mỗi ngày."
    buttonText="Khám phá ngay"
    buttonUrl="{{ route('map.index') }}"
    variant="brand"
/>

<div class="md:hidden">
    <x-stitch.bottom-nav active="home" />
</div>
@endsection
