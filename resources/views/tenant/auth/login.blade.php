@extends('layouts.app')

@section('title', 'Đăng nhập - '.($branding['app_name'] ?? 'HOSTY'))

@section('body')
<div class="min-h-screen flex flex-col items-center justify-center p-4 bg-[#f7f7f7]">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <x-hosty-logo href="/" class="justify-center !text-2xl" />
            <p class="text-gray-500 text-sm mt-3">Cổng khách thuê</p>
        </div>
        <form method="POST" action="{{ route('tenant.login') }}" class="hosty-card p-6 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700">Số điện thoại</label>
                <input name="phone" type="tel" value="{{ old('phone') }}" required class="mt-1.5 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)]" placeholder="0909123456">
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Mật khẩu</label>
                <input name="password" type="password" required class="mt-1.5 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-[var(--color-primary)] focus:ring-1 focus:ring-[var(--color-primary)]">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-[var(--color-primary)]"> Ghi nhớ đăng nhập
            </label>
            <button type="submit" class="hosty-btn w-full">Đăng nhập</button>
        </form>
        <p class="text-xs text-gray-400 text-center mt-6">Demo: 0909123456 / password</p>
    </div>
</div>
@endsection
