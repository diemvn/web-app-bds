@extends('layouts.public')

@section('title', 'Hỗ trợ - '.($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0')

@section('body')
<div class="pt-20 md:pt-28 pb-16 bg-surface-container-lowest border-b border-outline-variant/30">
    <div class="taste-container text-center max-w-3xl">
        <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-6">Trung tâm hỗ trợ</h1>
        <p class="text-lg text-on-surface-variant">Chúng tôi luôn sẵn sàng hỗ trợ bạn. Vui lòng xem các câu hỏi thường gặp hoặc gửi yêu cầu trực tiếp.</p>
    </div>
</div>

<div class="taste-container py-16 md:py-24">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-24">
        
        <!-- FAQs -->
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-on-surface mb-8">Câu hỏi thường gặp</h2>
            
            @if($faqsByCategory->count() > 0)
                <div class="space-y-8" x-data="{ activeCategory: '{{ $faqsByCategory->keys()->first() }}', activeFaq: null }">
                    <!-- Categories -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($faqsByCategory->keys() as $category)
                            <button type="button" 
                                @click="activeCategory = '{{ $category }}'"
                                :class="activeCategory === '{{ $category }}' ? 'bg-primary text-on-primary' : 'bg-surface-container border border-outline-variant text-on-surface hover:bg-surface-container-high'"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-colors tactile-feedback">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>

                    <!-- FAQ Items -->
                    @foreach($faqsByCategory as $category => $faqs)
                        <div x-show="activeCategory === '{{ $category }}'" class="space-y-4">
                            @foreach($faqs as $faq)
                                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl overflow-hidden transition-all duration-300"
                                     :class="activeFaq === {{ $faq->id }} ? 'shadow-md border-primary/30' : 'hover:border-outline-variant'">
                                    <button type="button" @click="activeFaq = activeFaq === {{ $faq->id }} ? null : {{ $faq->id }}" 
                                            class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none">
                                        <span class="font-semibold text-on-surface text-lg pr-4">{{ $faq->question }}</span>
                                        <span class="material-symbols-outlined text-on-surface-variant transition-transform duration-300"
                                              :class="activeFaq === {{ $faq->id }} ? 'rotate-180' : ''">
                                            expand_more
                                        </span>
                                    </button>
                                    <div x-show="activeFaq === {{ $faq->id }}" x-collapse>
                                        <div class="px-6 pb-6 text-on-surface-variant prose prose-p:text-on-surface-variant max-w-none">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-surface-container-lowest rounded-2xl border border-outline-variant border-dashed">
                    <p class="text-on-surface-variant">Đang cập nhật các câu hỏi thường gặp.</p>
                </div>
            @endif
        </div>

        <!-- Contact Form -->
        <div class="w-full lg:w-[450px]">
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-3xl p-6 md:p-8 shadow-sm sticky top-24">
                <h2 class="text-2xl font-bold text-on-surface mb-2">Gửi yêu cầu hỗ trợ</h2>
                <p class="text-on-surface-variant mb-6 text-sm">Điền thông tin bên dưới, chúng tôi sẽ liên hệ lại nhanh nhất có thể.</p>

                @if(session('success'))
                    <div class="bg-primary-container text-on-primary-container p-4 rounded-xl mb-6 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary">check_circle</span>
                        <div class="text-sm font-medium">{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('support.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-on-surface mb-1">Họ tên *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}"
                               class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-on-surface mb-1">Số điện thoại *</label>
                        <input type="tel" id="phone" name="phone" required value="{{ old('phone', auth()->user()->phone ?? '') }}"
                               class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('phone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-on-surface mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                               class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-on-surface mb-1">Vấn đề cần hỗ trợ *</label>
                        <input type="text" id="subject" name="subject" required value="{{ old('subject') }}"
                               class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('subject') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-on-surface mb-1">Nội dung *</label>
                        <textarea id="message" name="message" required rows="4"
                                  class="w-full px-4 py-3 bg-surface border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">{{ old('message') }}</textarea>
                        @error('message') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-primary text-on-primary rounded-xl font-bold transition-colors hover:bg-primary-fixed-variant tactile-feedback mt-2">
                        Gửi yêu cầu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="md:hidden">
    <x-stitch.bottom-nav active="support" />
</div>
@endsection
