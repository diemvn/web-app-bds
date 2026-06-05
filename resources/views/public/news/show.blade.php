@extends('layouts.public')

@section('title', $article->seo_title ?? $article->title . ' - '.($branding['app_name'] ?? 'HOSTY'))
@section('meta_description', $article->seo_description ?? $article->excerpt ?? Str::limit(strip_tags($article->content), 160))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0')

@section('body')
<!-- Article Header -->
<div class="bg-surface-container-lowest border-b border-outline-variant/30 pt-20 md:pt-28 pb-10">
    <div class="taste-container max-w-3xl">
        <x-public.breadcrumb :items="[
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Tin tức', 'url' => route('news.index')],
            ['label' => $article->category->name ?? 'Danh mục', 'url' => route('news.index', ['category' => $article->category->slug ?? ''])],
            ['label' => Str::limit($article->title, 30), 'url' => null],
        ]" class="mb-6" />

        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-on-surface mb-6 leading-tight">
            {{ $article->title }}
        </h1>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold overflow-hidden">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-on-surface">{{ $article->author->name ?? 'Quản trị viên' }}</div>
                        <div class="text-xs text-on-surface-variant flex items-center gap-1">
                            <span>{{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}</span>
                            @if($article->reading_time)
                                <span>•</span>
                                <span>{{ $article->reading_time }} phút đọc</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="w-10 h-10 rounded-full bg-surface border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-container hover:text-primary transition-colors tactile-feedback" aria-label="Chia sẻ">
                    <span class="material-symbols-outlined text-[20px]">share</span>
                </button>
                <button class="w-10 h-10 rounded-full bg-surface border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-container hover:text-primary transition-colors tactile-feedback" aria-label="Lưu bài viết">
                    <span class="material-symbols-outlined text-[20px]">bookmark_border</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Article Content -->
<div class="taste-container max-w-3xl py-10 md:py-16">
    @if($article->thumbnail)
        <div class="w-full h-[300px] md:h-[450px] rounded-3xl overflow-hidden mb-12 shadow-sm border border-outline-variant/30">
            <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    @if($article->excerpt)
        <div class="text-xl md:text-2xl font-medium text-on-surface mb-8 leading-relaxed italic border-l-4 border-primary pl-6 py-2">
            {{ $article->excerpt }}
        </div>
    @endif

    <article class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-bold prose-a:text-primary hover:prose-a:text-primary-fixed-variant prose-img:rounded-2xl">
        {!! $article->content !!}
    </article>

    <!-- Tags/Category -->
    <div class="mt-12 pt-8 border-t border-outline-variant/30 flex items-center gap-4">
        <span class="text-sm font-bold text-on-surface">Chuyên mục:</span>
        <a href="{{ route('news.index', ['category' => $article->category->slug ?? '']) }}" class="px-4 py-1.5 rounded-full bg-surface-container-low text-sm font-medium text-on-surface hover:bg-surface-container transition-colors">
            {{ $article->category->name ?? 'Tin tức' }}
        </a>
    </div>
</div>

<!-- Related Articles -->
@if($relatedArticles->count() > 0)
<div class="bg-surface-container-lowest border-t border-outline-variant/30 py-16">
    <div class="taste-container">
        <h2 class="text-2xl font-bold text-on-surface mb-8">Bài viết liên quan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedArticles as $related)
                <x-public.news-card :article="$related" />
            @endforeach
        </div>
    </div>
</div>
@endif

<x-public.cta-section 
    headline="Bạn đang tìm kiếm phòng trọ?"
    subtext="Khám phá hàng ngàn phòng trọ, căn hộ chất lượng được cập nhật liên tục."
    buttonText="Khám phá ngay"
    buttonUrl="{{ route('map.index') }}"
    variant="brand"
/>

<div class="md:hidden">
    <x-stitch.bottom-nav active="news" />
</div>
@endsection
