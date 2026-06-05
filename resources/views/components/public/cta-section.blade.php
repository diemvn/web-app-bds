@props([
    'headline' => 'Bạn muốn đăng tin cho thuê phòng?',
    'subtext' => 'Tiếp cận hàng ngàn người thuê tiềm năng mỗi ngày. Đăng tin nhanh chóng, quản lý dễ dàng.',
    'buttonText' => 'Đăng tin ngay',
    'buttonUrl' => '#',
    'variant' => 'brand' // 'brand' | 'subtle' | 'outline'
])

@php
    $bgClass = match($variant) {
        'brand' => 'bg-primary text-on-primary',
        'subtle' => 'bg-surface-container text-on-surface',
        'outline' => 'bg-surface border border-outline-variant text-on-surface',
        default => 'bg-primary text-on-primary',
    };
    
    $btnClass = match($variant) {
        'brand' => 'bg-surface text-primary hover:bg-surface-container-low',
        'subtle', 'outline' => 'bg-primary text-on-primary hover:bg-primary-fixed-variant',
        default => 'bg-surface text-primary hover:bg-surface-container-low',
    };
@endphp

<section class="taste-section taste-container">
    <div class="{{ $bgClass }} rounded-[2rem] px-6 py-12 md:py-16 text-center stitch-section">
        <div class="max-w-2xl mx-auto flex flex-col items-center">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">{{ $headline }}</h2>
            @if($subtext)
                <p class="text-base md:text-lg opacity-90 mb-8 max-w-lg mx-auto leading-relaxed">{{ $subtext }}</p>
            @endif
            <a href="{{ $buttonUrl }}" class="{{ $btnClass }} px-8 py-3.5 rounded-full font-semibold transition-all hover:shadow-lg tactile-feedback">
                {{ $buttonText }}
            </a>
        </div>
    </div>
</section>
