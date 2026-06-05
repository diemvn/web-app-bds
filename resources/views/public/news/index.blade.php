@extends('layouts.public')

@section('title', 'Tin tức - '.($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0')

@section('body')
<div class="pt-20 md:pt-24 pb-12 bg-surface-container-lowest border-b border-outline-variant/30">
    <div class="taste-container text-center max-w-3xl">
        <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">Tin tức & Cẩm nang</h1>
        <p class="text-lg text-on-surface-variant">Khám phá những mẹo vặt, kinh nghiệm tìm phòng và không gian sống hiện đại</p>
    </div>
</div>

<div class="taste-container py-8 md:py-12">
    <!-- Category Filter -->
    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-10 pb-4 border-b border-outline-variant/30 overflow-x-auto hide-scrollbar">
        <a href="{{ route('news.index') }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap {{ !request('category') ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
            Tất cả tin tức
        </a>
        @foreach($categories as $category)
            <a href="{{ route('news.index', ['category' => $category->slug]) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap {{ request('category') === $category->slug ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    @if($articles->count() > 0)
        <!-- Featured Article (if on page 1 and no category filter) -->
        @if($articles->currentPage() === 1 && !request('category') && $articles->first())
            @php $featured = $articles->shift(); @endphp
            <div class="mb-12 group cursor-pointer relative rounded-3xl overflow-hidden bg-surface-container-lowest border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
                <a href="{{ route('news.show', $featured->slug) }}" class="absolute inset-0 z-10"><span class="sr-only">Đọc bài viết</span></a>
                <div class="flex flex-col md:flex-row h-full">
                    <div class="w-full md:w-3/5 h-64 md:h-[400px] bg-surface-container-high relative overflow-hidden">
                        @if($featured->thumbnail)
                            <img src="{{ Storage::url($featured->thumbnail) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primary-container/20">
                                <span class="material-symbols-outlined text-[64px] text-primary/30">image</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1 bg-surface/90 backdrop-blur-md rounded-full text-xs font-bold text-primary">{{ $featured->category->name ?? 'Tin tức' }}</span>
                        </div>
                    </div>
                    <div class="w-full md:w-2/5 p-6 md:p-10 flex flex-col justify-center">
                        <div class="flex items-center gap-2 text-sm text-on-surface-variant mb-4">
                            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                            <span>{{ $featured->published_at ? $featured->published_at->format('d/m/Y') : $featured->created_at->format('d/m/Y') }}</span>
                            @if($featured->reading_time)
                                <span class="mx-1">•</span>
                                <span class="material-symbols-outlined text-[18px]">schedule</span>
                                <span>{{ $featured->reading_time }} phút đọc</span>
                            @endif
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-on-surface mb-4 group-hover:text-primary transition-colors leading-tight">
                            {{ $featured->title }}
                        </h2>
                        <p class="text-on-surface-variant mb-6 line-clamp-3">
                            {{ $featured->excerpt ?? Str::limit(strip_tags($featured->content), 150) }}
                        </p>
                        <div class="mt-auto flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold overflow-hidden">
                                    <span class="material-symbols-outlined text-[20px]">person</span>
                                </div>
                                <span class="text-sm font-medium text-on-surface">{{ $featured->author->name ?? 'Quản trị viên' }}</span>
                            </div>
                            <span class="text-primary font-medium flex items-center gap-1 group-hover:gap-2 transition-all">
                                Đọc ngay <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($articles as $article)
                <x-public.news-card :article="$article" />
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $articles->links() }}
        </div>
    @else
        <div class="py-20 text-center bg-surface-container-lowest rounded-3xl border border-outline-variant/50 border-dashed">
            <span class="material-symbols-outlined text-[64px] text-outline-variant mb-4">article</span>
            <h2 class="text-2xl font-bold text-on-surface mb-2">Chưa có bài viết nào</h2>
            <p class="text-on-surface-variant">Vui lòng quay lại sau để cập nhật những tin tức mới nhất.</p>
        </div>
    @endif
</div>

<x-public.cta-section 
    headline="Nhận thông báo khi có phòng mới"
    subtext="Đăng ký nhận bản tin để không bỏ lỡ những phòng trọ phù hợp và các kinh nghiệm hữu ích."
    buttonText="Đăng ký ngay"
    buttonUrl="#"
    variant="neutral"
/>

<div class="md:hidden">
    <x-stitch.bottom-nav active="news" />
</div>
@endsection
