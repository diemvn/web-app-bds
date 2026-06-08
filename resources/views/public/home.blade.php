@extends('layouts.public')

@section('title', 'Trang chủ - '.($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0')

@section('body')
<!-- Hero Section -->
<section class="relative bg-surface min-h-[85vh] flex items-center border-b border-outline-variant/30 overflow-hidden pt-20 pb-16 md:py-24">
    <!-- Abstract background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-secondary/5 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/3"></div>
    </div>

    <div class="taste-container w-full relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
        <!-- Left: Hook & Search -->
        <div class="lg:col-span-6 flex flex-col items-start text-left z-20">
            <div class="hero-content opacity-0 translate-y-8">
                <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-surface-container-high border border-outline-variant/50 text-on-surface-variant text-sm font-medium mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    Nền tảng tìm phòng thế hệ mới
                </span>
                
                <h1 class="text-4xl md:text-5xl lg:text-[4rem] font-bold tracking-tight text-on-surface mb-6 leading-[1.1]">
                    Thuê phòng an tâm.<br>
                    <span class="text-primary relative inline-block mt-2">
                        Không lo ảo giá.
                        <!-- Decorative underline -->
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-secondary opacity-50" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 15 100 5" stroke="currentColor" stroke-width="4" fill="none"/></svg>
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-on-surface-variant max-w-xl mb-10 leading-relaxed">
                    Trải nghiệm tìm kiếm không gian sống thông minh, chính xác qua bản đồ. Làm việc trực tiếp với chủ nhà, minh bạch mọi chi phí.
                </p>
            </div>

            <!-- Big Search Bar -->
            <div class="hero-search opacity-0 scale-95 w-full max-w-xl bg-surface p-2.5 rounded-[2rem] shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] border border-outline-variant/50 flex flex-col md:flex-row gap-2 relative z-30">
                <form action="{{ route('map.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-2 w-full">
                    <div class="flex-1 flex items-center px-4 h-14 bg-surface-container-lowest rounded-full border border-outline-variant/30 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all group">
                        <span class="material-symbols-outlined text-primary mr-3 group-focus-within:animate-bounce">location_on</span>
                        <input type="text" name="q" placeholder="Bạn muốn thuê ở quận nào?" class="w-full bg-transparent border-none focus:ring-0 text-base text-on-surface placeholder:text-on-surface-variant/70 p-0 outline-none">
                    </div>
                    <button type="submit" class="bg-primary text-on-primary h-14 px-8 rounded-full font-bold text-base transition-all hover:bg-primary-fixed-variant hover:shadow-lg hover:-translate-y-0.5 tactile-feedback flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                        Tìm ngay
                    </button>
                </form>
            </div>

            <div class="hero-tags opacity-0 mt-8 flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-on-surface-variant">Gợi ý siêu tốc:</span>
                <a href="{{ route('map.index', ['q' => 'Quận 1']) }}" class="px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">Quận 1</a>
                <a href="{{ route('map.index', ['q' => 'Bình Thạnh']) }}" class="px-4 py-2 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">Bình Thạnh</a>
            </div>
        </div>

        <!-- Right: Features Bento / Floating UI -->
        <div class="lg:col-span-6 relative h-[500px] hidden lg:block" style="perspective: 1000px;">
            <!-- Center piece: Interactive Map Snippet -->
            <div class="hero-feature-card map-card absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[340px] bg-surface rounded-[2rem] p-3 shadow-2xl border border-outline-variant/30 z-10">
                <div class="w-full h-[220px] bg-surface-container-high rounded-2xl overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover opacity-80" alt="Map">
                    <!-- Marker -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center animate-bounce">
                        <div class="bg-primary text-white font-bold px-3 py-1 rounded-full shadow-lg text-sm mb-1">4.5tr</div>
                        <div class="w-0 h-0 border-l-[6px] border-l-transparent border-t-[8px] border-t-primary border-r-[6px] border-r-transparent"></div>
                    </div>
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-bold text-lg text-on-surface">Tìm qua Bản đồ</h3>
                    <p class="text-sm text-on-surface-variant">Trực quan vị trí & tiện ích xung quanh</p>
                </div>
            </div>

            <!-- Floating Card 1: Verified -->
            <div class="hero-feature-card verified-card absolute top-[10%] left-[5%] w-[220px] bg-surface/90 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-outline-variant/30 z-20 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center text-success shrink-0 border border-success/20">
                    <span class="material-symbols-outlined text-[24px]">verified</span>
                </div>
                <div>
                    <h4 class="font-bold text-on-surface text-sm">Phòng chính chủ</h4>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">100% thông tin đã xác thực</p>
                </div>
            </div>

            <!-- Floating Card 2: Chat -->
            <div class="hero-feature-card chat-card absolute bottom-[15%] right-[5%] w-[240px] bg-surface/90 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-outline-variant/30 z-20">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary border border-primary/20">
                        <span class="material-symbols-outlined text-[20px]">forum</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface text-sm">Chủ nhà A</h4>
                        <p class="text-xs text-success flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Đang trực tuyến
                        </p>
                    </div>
                </div>
                <div class="bg-surface-container-lowest rounded-xl rounded-tl-sm p-3 text-sm text-on-surface border border-outline-variant/20 shadow-sm">
                    Chào bạn, phòng vẫn còn trống nhé!
                </div>
            </div>
            
            <!-- Floating Card 3: Filter -->
            <div class="hero-feature-card filter-card absolute top-[60%] -left-[10%] w-[180px] bg-surface/90 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-outline-variant/30 z-20">
                <h4 class="font-bold text-on-surface text-sm mb-3">Lọc siêu thông minh</h4>
                <div class="space-y-3">
                    <div class="h-1.5 w-full bg-surface-container-highest rounded-full overflow-hidden">
                        <div class="h-full bg-primary w-[60%] rounded-full"></div>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-1 bg-surface-container rounded-md text-[10px] font-medium text-on-surface-variant border border-outline-variant/20">Máy lạnh</span>
                        <span class="px-2 py-1 bg-surface-container rounded-md text-[10px] font-medium text-on-surface-variant border border-outline-variant/20">Ban công</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if(typeof gsap !== 'undefined') {
        const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
        
        // Left Column Entrance
        tl.to(".hero-content", { y: 0, opacity: 1, duration: 1, stagger: 0.2 })
          .to(".hero-search", { scale: 1, opacity: 1, duration: 0.8, ease: "back.out(1.5)" }, "-=0.6")
          .to(".hero-tags", { opacity: 1, duration: 0.5 }, "-=0.4");
          
        // Right Column Floating Cards Entrance
        tl.fromTo(".map-card", 
            { y: 50, opacity: 0, rotateY: -20, rotateX: 10 },
            { y: 0, opacity: 1, rotateY: -10, rotateX: 5, duration: 1.2 },
            "-=1"
          )
          .fromTo(".verified-card",
            { x: -50, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.8, ease: "back.out(1.2)" },
            "-=0.8"
          )
          .fromTo(".chat-card",
            { x: 50, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.8, ease: "back.out(1.2)" },
            "-=0.6"
          )
          .fromTo(".filter-card",
            { y: 50, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.8, ease: "back.out(1.2)" },
            "-=0.6"
          );

        // Continuous Floating Animation
        gsap.to(".map-card", {
            y: "-=15",
            rotationY: "-15deg",
            duration: 4,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });
        
        gsap.to(".verified-card", {
            y: "+=10",
            duration: 3.5,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: 0.5
        });
        
        gsap.to(".chat-card", {
            y: "-=12",
            duration: 4.2,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: 1
        });
        
        gsap.to(".filter-card", {
            y: "+=8",
            duration: 3.8,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: 1.5
        });
    }
});
</script>
@endpush
