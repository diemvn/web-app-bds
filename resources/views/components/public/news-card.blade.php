@props(['article'])

<article class="stitch-card group flex flex-col bg-surface rounded-2xl border border-outline-variant overflow-hidden h-full">
    <a href="{{ route('news.show', $article->slug ?? 'slug') }}" class="block relative aspect-video overflow-hidden bg-surface-container">
        @if(!empty($article->thumbnail) || !empty($article->featured_image))
            <img src="{{ asset('storage/' . ($article->thumbnail ?? $article->featured_image)) }}" 
                 alt="{{ $article->title }}" 
                 class="stitch-card-image w-full h-full object-cover" 
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-on-surface-variant bg-surface-variant">
                <span class="material-symbols-outlined text-[48px] opacity-50">image</span>
            </div>
        @endif
        
        @if(!empty($article->category))
            <span class="absolute top-3 left-3 px-3 py-1 bg-surface/90 backdrop-blur-md text-on-surface text-[11px] font-bold rounded-full shadow-sm z-10">
                {{ $article->category->name }}
            </span>
        @endif
    </a>
    
    <div class="flex flex-col flex-1 p-5">
        <div class="flex items-center gap-3 text-xs text-on-surface-variant mb-3">
            <time datetime="{{ optional($article->published_at ?? $article->created_at)->toIso8601String() }}">
                {{ optional($article->published_at ?? $article->created_at)->format('d/m/Y') }}
            </time>
            @if(!empty($article->reading_time))
                <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                <span>{{ $article->reading_time }} phút đọc</span>
            @endif
        </div>
        
        <h3 class="title-md text-on-surface group-hover:text-primary transition-colors line-clamp-2 mb-2">
            <a href="{{ route('news.show', $article->slug ?? 'slug') }}">
                {{ $article->title }}
            </a>
        </h3>
        
        <p class="text-sm text-on-surface-variant line-clamp-3 mb-4 flex-1">
            {{ $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content ?? ''), 120) }}
        </p>
        
        <div class="flex items-center gap-2 mt-auto pt-4 border-t border-outline-variant/30">
            <div class="w-6 h-6 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-[10px] font-bold">
                {{ substr(optional($article->author)->name ?? 'A', 0, 1) }}
            </div>
            <span class="text-xs font-medium text-on-surface">{{ optional($article->author)->name ?? 'Admin' }}</span>
        </div>
    </div>
</article>
