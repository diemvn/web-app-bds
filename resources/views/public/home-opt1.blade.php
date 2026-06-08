@extends('layouts.public')

@section('title', 'Option 1: Search-Centric - ' . ($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0 overflow-x-hidden')

@section('body')
<!-- HERO SECTION -->
<section class="relative bg-surface-container-lowest min-h-[85vh] flex items-center border-b border-outline-variant/30 overflow-hidden">
    <div class="taste-container w-full relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-20">
        
        <!-- Left Content -->
        <div class="flex flex-col items-start text-left">
            <div class="opt1-hero-text opacity-0 translate-y-8">
                <span class="inline-block py-1.5 px-3 rounded-full bg-primary-container text-on-primary-container text-xs font-bold tracking-wider uppercase mb-6">Mới: Nền tảng siêu việt</span>
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold tracking-tight text-on-surface mb-6 leading-[1.1]">
                    Tìm phòng trọ lý tưởng<br>
                    <span class="text-primary relative inline-block">
                        dễ dàng hơn bao giờ hết
                        <!-- Decorative underline -->
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-secondary opacity-50" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 15 100 5" stroke="currentColor" stroke-width="4" fill="none"/></svg>
                    </span>
                </h1>
                <p class="text-lg md:text-xl text-on-surface-variant max-w-xl mb-10">
                    Khám phá hàng ngàn không gian sống chất lượng cao. Thông tin xác thực, hình ảnh chân thật, giao dịch trực tiếp.
                </p>
            </div>

            <!-- Floating Search Bar -->
            <div class="opt1-search opacity-0 scale-95 w-full max-w-xl bg-surface p-2.5 rounded-[2rem] shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] border border-outline-variant/50 flex flex-col md:flex-row gap-2">
                <div class="flex-1 flex items-center px-4 h-14 bg-surface-container-lowest rounded-full border border-outline-variant/30 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all">
                    <span class="material-symbols-outlined text-primary mr-3">location_on</span>
                    <input type="text" placeholder="Bạn muốn thuê ở đâu?" class="w-full bg-transparent border-none focus:ring-0 text-base text-on-surface placeholder:text-on-surface-variant/70 p-0 outline-none">
                </div>
                <button class="bg-primary text-on-primary h-14 px-8 rounded-full font-bold text-base transition-all hover:bg-primary-fixed-variant hover:shadow-lg hover:-translate-y-0.5 tactile-feedback flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                    Tìm kiếm
                </button>
            </div>

            <div class="opt1-tags opacity-0 mt-8 flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-on-surface-variant">Phổ biến:</span>
                <a href="#" class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-medium text-on-surface hover:bg-surface-container transition-colors tactile-feedback">Quận 1</a>
                <a href="#" class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-medium text-on-surface hover:bg-surface-container transition-colors tactile-feedback">Bình Thạnh</a>
                <a href="#" class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-medium text-on-surface hover:bg-surface-container transition-colors tactile-feedback">Phú Nhuận</a>
            </div>
        </div>

        <!-- Right Visual (Overlapping Cards) -->
        <div class="relative hidden lg:block h-[600px]">
            <div class="opt1-image absolute top-10 right-0 w-[400px] h-[500px] bg-surface-container rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/20 z-10 origin-bottom-right">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover" alt="Interior">
                <div class="absolute bottom-4 left-4 right-4 bg-surface/90 backdrop-blur-md rounded-2xl p-4 shadow-lg border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-on-surface">Căn hộ Dịch vụ Cao cấp</h4>
                            <p class="text-sm text-on-surface-variant">Quận 1, TP.HCM</p>
                        </div>
                        <span class="font-bold text-primary">8.5tr</span>
                    </div>
                </div>
            </div>
            <div class="opt1-image absolute bottom-0 left-10 w-[300px] h-[350px] bg-secondary-container rounded-3xl overflow-hidden shadow-xl border border-outline-variant/20 z-20 origin-bottom-left">
                <img src="https://images.unsplash.com/photo-1502672260266-1c1de2d93688?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover" alt="Interior">
            </div>
        </div>
    </div>
    
    <!-- Decorative Blurs -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] pointer-events-none translate-x-1/3 -translate-y-1/3"></div>
</section>

<!-- HORIZONTAL SCROLL FEATURED -->
<section class="py-24 bg-surface relative overflow-hidden">
    <div class="taste-container mb-12 flex items-end justify-between">
        <div class="opt1-section-header opacity-0 translate-y-8">
            <h2 class="text-3xl md:text-4xl font-bold text-on-surface mb-3">Không gian nổi bật</h2>
            <p class="text-lg text-on-surface-variant">Lựa chọn hàng đầu dành cho bạn</p>
        </div>
        <a href="#" class="hidden md:flex items-center gap-2 text-primary font-bold hover:text-primary-fixed-variant transition-colors group">
            Khám phá thêm
            <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
        </a>
    </div>

    <!-- Horizontal scroll wrapper -->
    <div class="opt1-scroll-container w-full overflow-x-auto hide-scrollbar pl-4 md:pl-8 lg:pl-[max(2rem,calc((100vw-1200px)/2))] pb-8">
        <div class="flex gap-6 w-max pr-8">
            @forelse($listings->take(6) as $listing)
                <div class="w-[320px] md:w-[380px] shrink-0">
                    <x-stitch.listing-card :listing="$listing" />
                </div>
            @empty
                <p>Chưa có dữ liệu</p>
            @endforelse
        </div>
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
    // Register ScrollTrigger
    gsap.registerPlugin(ScrollTrigger);

    // Hero Animation Timeline
    const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
    
    tl.to(".opt1-hero-text", { y: 0, opacity: 1, duration: 1, stagger: 0.2 })
      .to(".opt1-search", { scale: 1, opacity: 1, duration: 0.8, ease: "back.out(1.5)" }, "-=0.6")
      .to(".opt1-tags", { opacity: 1, duration: 0.5 }, "-=0.4")
      .fromTo(".opt1-image", 
        { y: 50, opacity: 0, rotate: -5 },
        { y: 0, opacity: 1, rotate: 0, duration: 1, stagger: 0.2 },
        "-=1"
      );

    // Section Animation
    gsap.to(".opt1-section-header", {
        scrollTrigger: {
            trigger: ".opt1-section-header",
            start: "top 80%",
        },
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: "power2.out"
    });
});
</script>
@endpush
