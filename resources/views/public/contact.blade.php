@extends('layouts.public')

@section('body_class', 'text-on-surface')

@section('body')
@php
    $priceFormatted = number_format($listing->price, 0, ',', '.');
    $district = $listing->room?->building?->district ?? 'TP.HCM';
@endphp

<header class="sticky top-0 z-50 flex items-center bg-surface dark:bg-background border-b border-outline-variant px-margin-mobile h-16 w-full max-w-container-max mx-auto">
<a href="{{ route('listing.show', $listing->slug) }}" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-surface-container-low transition-colors active:scale-95">
<span class="material-symbols-outlined text-primary">arrow_back</span>
</a>
<h1 class="ml-4 text-headline-lg-mobile font-headline-lg-mobile text-on-surface">Liên hệ chính chủ</h1>
</header>

<main class="stitch-page-enter w-full max-w-xl mx-auto px-margin-mobile py-lg pb-32">
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_12px_rgba(0,0,0,0.05)] p-md mb-lg flex items-center gap-md border border-outline-variant/30">
<div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0">
@if($listing->thumbnail_url)
<img class="w-full h-full object-cover" alt="" src="{{ $listing->thumbnail_url }}">
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-3xl">🏠</div>
@endif
</div>
<div class="flex flex-col gap-1">
<p class="text-label-md font-label-md text-secondary">{{ $district }}</p>
<h2 class="text-title-lg font-title-lg text-on-surface line-clamp-1">{{ $listing->title }}</h2>
<p class="text-primary font-bold text-body-lg">{{ $priceFormatted }}đ/tháng</p>
</div>
</div>

<section class="bg-surface-container-lowest rounded-xl p-lg shadow-[0px_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant/30">
<h3 class="text-title-lg font-title-lg mb-md text-on-surface">Thông tin của bạn</h3>
<form class="space-y-lg" method="POST" action="{{ route('listing.contact.store', $listing->slug) }}" id="bookingForm">
@csrf
<div class="space-y-base">
<label class="text-label-md font-label-md text-on-surface-variant block">Họ và tên</label>
<input name="name" value="{{ old('name') }}" class="w-full h-12 px-md rounded-lg border border-outline-variant bg-surface focus:outline-none form-input-focus text-body-md" placeholder="Nhập họ và tên..." required type="text">
@error('name')<p class="text-error text-label-sm mt-1">{{ $message }}</p>@enderror
</div>
<div class="space-y-base">
<label class="text-label-md font-label-md text-on-surface-variant block">Số điện thoại</label>
<input name="phone" value="{{ old('phone') }}" class="w-full h-12 px-md rounded-lg border border-outline-variant bg-surface focus:outline-none form-input-focus text-body-md" placeholder="Nhập số điện thoại..." required type="tel">
@error('phone')<p class="text-error text-label-sm mt-1">{{ $message }}</p>@enderror
</div>
<div class="space-y-base">
<label class="text-label-md font-label-md text-on-surface-variant block">Ngày dọn vào</label>
<input name="move_in_date" value="{{ old('move_in_date') }}" class="w-full h-12 px-md rounded-lg border border-outline-variant bg-surface focus:outline-none form-input-focus text-body-md" type="date">
</div>
<div class="space-y-base">
<label class="text-label-md font-label-md text-on-surface-variant block">Lời nhắn (Tùy chọn)</label>
<textarea name="message" class="w-full p-md rounded-lg border border-outline-variant bg-surface focus:outline-none form-input-focus text-body-md resize-none" rows="4" placeholder="Tôi muốn xem phòng này vào lúc...">{{ old('message') }}</textarea>
</div>
<div class="pt-md md:static">
<button class="stitch-cta-primary w-full h-14 font-bold text-body-lg rounded-xl flex items-center justify-center gap-sm" type="submit">
<span class="material-symbols-outlined">send</span>
                        Gửi yêu cầu
                    </button>
</div>
</form>
</section>

<div class="mt-xl text-center">
<p class="text-label-md font-label-md text-on-surface-variant mb-md">Hoặc liên hệ nhanh qua</p>
<div class="flex justify-center gap-xl">
<a class="flex flex-col items-center gap-base active:opacity-70" href="#">
<div class="w-14 h-14 rounded-full bg-[#25D366] flex items-center justify-center text-white shadow-md">
<span class="material-symbols-outlined text-[32px]">chat</span>
</div>
<span class="text-label-sm font-label-sm">Zalo</span>
</a>
<a class="flex flex-col items-center gap-base active:opacity-70" href="tel:{{ $branding['contact_phone'] ?? '0901000000' }}">
<div class="w-14 h-14 rounded-full bg-primary flex items-center justify-center text-white shadow-md">
<span class="material-symbols-outlined text-[32px]">call</span>
</div>
<span class="text-label-sm font-label-sm">Gọi điện</span>
</a>
</div>
</div>
</main>

<div class="md:hidden fixed bottom-0 left-0 w-full p-margin-mobile bg-surface-container-lowest border-t border-outline-variant flex items-center gap-md z-40">
<button type="button" class="stitch-cta-primary flex-1 h-12 font-bold rounded-lg" onclick="document.getElementById('bookingForm').requestSubmit()">
            Gửi yêu cầu ngay
        </button>
</div>
@endsection
