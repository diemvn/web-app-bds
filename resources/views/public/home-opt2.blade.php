@extends('layouts.public')

@section('title', 'Option 2: Bento Box - ' . ($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface-container-lowest text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0 overflow-x-hidden')

@section('body')
<!-- HERO BENTO GRID -->
<section class="taste-container py-8 md:py-12">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 min-h-[600px] auto-rows-[200px] md:auto-rows-min">
        
        <!-- Main Search Bento (Spans 8 cols) -->
        <div class="opt2-bento opacity-0 md:col-span-8 row-span-2 bg-surface rounded-[2rem] p-8 md:p-12 shadow-sm border border-outline-variant/30 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent pointer-events-none"></div>
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-on-surface mb-6 leading-tight">
                    Khám phá <br>
                    <span class="text-primary">không gian mới</span>
                </h1>
                
                <div class="w-full bg-surface-container-lowest p-2 rounded-2xl shadow-sm border border-outline-variant flex flex-col md:flex-row gap-2 transition-all focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20">
                    <div class="flex-1 flex items-center px-4 h-12">
                        <span class="material-symbols-outlined text-primary mr-3">search</span>
                        <input type="text" placeholder="Tìm quận, đường, tên toà nhà..." class="w-full bg-transparent border-none focus:ring-0 text-base outline-none">
                    </div>
                    <button class="bg-primary text-on-primary h-12 px-6 rounded-xl font-bold transition-colors hover:bg-primary-fixed-variant tactile-feedback">
                        Tìm kiếm
                    </button>
                </div>
            </div>
            
            <!-- Decorative circle -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-150"></div>
        </div>

        <!-- Featured Map Bento (Spans 4 cols) -->
        <a href="{{ route('map.index') }}" class="opt2-bento opacity-0 md:col-span-4 row-span-1 bg-surface-container rounded-[2rem] p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between relative overflow-hidden group tactile-feedback">
            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-60 transition-opacity duration-500" alt="Map">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            
            <div class="relative z-10 flex justify-end">
                <div class="w-10 h-10 bg-surface/90 backdrop-blur-md rounded-full flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">map</span>
                </div>
            </div>
            <div class="relative z-10 mt-12">
                <h3 class="text-white font-bold text-xl">Tìm trên bản đồ</h3>
                <p class="text-white/80 text-sm mt-1">Trực quan và dễ dàng</p>
            </div>
        </a>

        <!-- Trending Bento (Spans 4 cols) -->
        <div class="opt2-bento opacity-0 md:col-span-4 row-span-1 bg-tertiary-container text-on-tertiary-container rounded-[2rem] p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between group cursor-pointer tactile-feedback">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 bg-white/50 backdrop-blur-md rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="bg-white/50 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Hot</span>
            </div>
            <div>
                <h3 class="font-bold text-xl">Quận Bình Thạnh</h3>
                <p class="text-sm opacity-80 mt-1">+150 phòng mới hôm nay</p>
            </div>
        </div>

        <!-- Utility Bento (Spans 3 cols) -->
        <div class="opt2-bento opacity-0 md:col-span-3 row-span-1 bg-surface rounded-[2rem] p-6 shadow-sm border border-outline-variant/30 flex flex-col items-center justify-center text-center group cursor-pointer tactile-feedback hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[40px] text-primary mb-3">apartment</span>
            <h3 class="font-bold">Căn hộ Mini</h3>
        </div>

        <!-- Utility Bento (Spans 3 cols) -->
        <div class="opt2-bento opacity-0 md:col-span-3 row-span-1 bg-surface rounded-[2rem] p-6 shadow-sm border border-outline-variant/30 flex flex-col items-center justify-center text-center group cursor-pointer tactile-feedback hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[40px] text-secondary mb-3">sell</span>
            <h3 class="font-bold">Ưu đãi giảm giá</h3>
        </div>

        <!-- Utility Bento (Spans 6 cols) -->
        <div class="opt2-bento opacity-0 md:col-span-6 row-span-1 bg-surface-container-high rounded-[2rem] p-6 shadow-sm flex items-center justify-between group cursor-pointer tactile-feedback overflow-hidden relative">
            <div class="relative z-10">
                <h3 class="font-bold text-xl text-on-surface">Đăng tin cho thuê</h3>
                <p class="text-sm text-on-surface-variant mt-1">Tiếp cận hàng ngàn người thuê</p>
            </div>
            <div class="relative z-10 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined">add</span>
            </div>
            <!-- Decorative curve -->
            <svg class="absolute right-0 bottom-0 h-full text-primary/10" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M100 100 L100 0 Q50 0 0 100 Z" fill="currentColor"/></svg>
        </div>

    </div>
</section>

<!-- LATEST LISTINGS -->
<section class="taste-section taste-container py-16">
    <div class="flex items-end justify-between mb-10">
        <div class="opt2-header opacity-0 translate-y-8">
            <h2 class="text-3xl font-bold text-on-surface">Mới nhất tuần này</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($listings->take(4) as $listing)
            <div class="opt2-card opacity-0 translate-y-12">
                <x-stitch.listing-card :listing="$listing" />
            </div>
        @empty
            <p>Chưa có dữ liệu</p>
        @endforelse
    </div>
</section>

<!-- Bottom Nav -->
<div class="md:hidden">
    <x-stitch.bottom-nav active="home" />
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.registerPlugin(ScrollTrigger);

    // Bento Grid Animation
    gsap.to(".opt2-bento", {
        y: 0,
        opacity: 1,
        duration: 0.8,
        stagger: 0.1,
        ease: "power3.out",
        delay: 0.1
    });

    // Listings Animation
    gsap.to(".opt2-header", {
        scrollTrigger: { trigger: ".opt2-header", start: "top 85%" },
        y: 0, opacity: 1, duration: 0.6, ease: "power2.out"
    });

    gsap.to(".opt2-card", {
        scrollTrigger: { trigger: ".opt2-card", start: "top 85%" },
        y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "back.out(1.2)"
    });
});
</script>
@endpush
