@extends('layouts.public')

@section('title', 'Option 3: Map-First - ' . ($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0 overflow-x-hidden')

@section('body')
<!-- HERO MAP FULLSCREEN -->
<section class="relative w-full h-[75vh] min-h-[600px] overflow-hidden bg-surface-container-highest">
    <!-- Map Background Image -->
    <div class="opt3-map-bg absolute inset-0 w-full h-full scale-110">
        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 mix-blend-luminosity" alt="Map Overview">
        <div class="absolute inset-0 bg-gradient-to-r from-surface/90 via-surface/50 to-transparent"></div>
    </div>

    <!-- Floating UI -->
    <div class="absolute inset-0 z-10 flex items-center">
        <div class="taste-container w-full">
            <div class="opt3-overlay-card opacity-0 -translate-x-12 max-w-lg bg-surface/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border border-white/20">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-bold mb-6">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    Live Map Search
                </span>
                
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-on-surface mb-4">
                    Tìm phòng theo <br>
                    <span class="text-primary">vị trí của bạn</span>
                </h1>
                
                <p class="text-on-surface-variant mb-8">
                    Trực quan, chính xác và nhanh chóng. Khám phá các căn hộ xung quanh khu vực bạn chọn.
                </p>

                <!-- Search form inside overlay -->
                <form action="{{ route('map.index') }}" class="flex flex-col gap-4">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">my_location</span>
                        <input type="text" placeholder="Nhập địa điểm..." class="w-full pl-12 pr-4 py-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/50 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-bold text-lg hover:bg-primary-fixed-variant transition-colors tactile-feedback flex items-center justify-center gap-2">
                        Mở bản đồ
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- CURATED SELECTIONS -->
<section class="taste-section taste-container py-20">
    <div class="opt3-header opacity-0 text-center max-w-2xl mx-auto mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-on-surface mb-4">Lựa chọn hàng đầu</h2>
        <p class="text-on-surface-variant text-lg">Những căn hộ được đánh giá cao nhất về vị trí và tiện ích.</p>
    </div>

    <!-- Alternating Layout -->
    <div class="space-y-24">
        @foreach($listings->take(3) as $index => $listing)
            <div class="opt3-row opacity-0 translate-y-16 flex flex-col {{ $index % 2 == 1 ? 'md:flex-row-reverse' : 'md:flex-row' }} gap-8 md:gap-16 items-center">
                <!-- Large Image -->
                <div class="w-full md:w-1/2 h-[300px] md:h-[450px] rounded-[2rem] overflow-hidden relative shadow-lg group">
                    <img src="{{ $listing->thumbnail_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=800' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Listing">
                    <div class="absolute top-4 left-4">
                        <span class="bg-surface/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-bold text-on-surface">{{ number_format($listing->price / 1000000, 1, ',', '.') }} triệu</span>
                    </div>
                </div>
                
                <!-- Details -->
                <div class="w-full md:w-1/2">
                    <h3 class="text-2xl md:text-3xl font-bold text-on-surface mb-4">{{ $listing->title }}</h3>
                    <p class="text-on-surface-variant mb-6 text-lg">{{ Str::limit($listing->description, 150) }}</p>
                    
                    <div class="flex items-center gap-6 mb-8">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-outline">straighten</span>
                            <span class="font-medium">{{ $listing->area }}m²</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-outline">location_on</span>
                            <span class="font-medium">{{ $listing->room?->building?->district ?? 'TP.HCM' }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('listing.show', $listing->slug ?? 'demo') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-surface-container-high hover:bg-surface-container text-on-surface font-bold transition-colors">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        @endforeach
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

    // Map Pan Animation
    gsap.to(".opt3-map-bg", {
        scale: 1,
        duration: 3,
        ease: "power2.out"
    });

    // Overlay Card Animation
    gsap.to(".opt3-overlay-card", {
        x: 0,
        opacity: 1,
        duration: 1,
        delay: 0.5,
        ease: "power3.out"
    });

    // Content Animations
    gsap.to(".opt3-header", {
        scrollTrigger: { trigger: ".opt3-header", start: "top 80%" },
        opacity: 1, duration: 1, ease: "power2.out"
    });

    const rows = document.querySelectorAll('.opt3-row');
    rows.forEach(row => {
        gsap.to(row, {
            scrollTrigger: {
                trigger: row,
                start: "top 80%",
            },
            y: 0,
            opacity: 1,
            duration: 1,
            ease: "power3.out"
        });
    });
});
</script>
@endpush
