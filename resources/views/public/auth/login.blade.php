@extends('layouts.public', ['noHeader' => true, 'noFooter' => true])

@section('title', 'Đăng nhập - '.($branding['app_name'] ?? 'HOSTY'))

@section('body')
<div class="min-h-screen flex flex-col md:flex-row bg-surface">
    <!-- Left: Branding / Illustration (Hidden on mobile) -->
    <div class="hidden md:flex md:w-1/2 lg:w-3/5 bg-primary-container p-12 flex-col justify-between relative overflow-hidden">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[500px] h-[500px] bg-primary rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[400px] h-[400px] bg-tertiary rounded-full blur-3xl opacity-20"></div>
        
        <div class="relative z-10">
            <x-hosty-logo href="/" class="!text-3xl text-on-primary-container" />
        </div>
        
        <div class="relative z-10 max-w-lg mb-12">
            <h1 class="text-4xl lg:text-5xl font-bold text-on-primary-container tracking-tight leading-tight mb-6">
                Tìm phòng trọ lý tưởng<br>với HOSTY
            </h1>
            <p class="text-lg text-on-primary-container/80 leading-relaxed">
                Đăng nhập để lưu phòng yêu thích, nhận thông báo mới nhất và quản lý hợp đồng thuê của bạn.
            </p>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center px-6 py-12 lg:px-16 min-h-screen">
        <div class="w-full max-w-md mx-auto">
            <!-- Mobile Header -->
            <div class="md:hidden flex justify-center mb-10">
                <x-hosty-logo href="/" class="!text-3xl" />
            </div>

            <div class="mb-10 text-center md:text-left stitch-fade-up">
                <h2 class="text-3xl font-bold tracking-tight text-on-surface mb-2">Đăng nhập</h2>
                <p class="text-on-surface-variant">Vui lòng điền thông tin để tiếp tục</p>
            </div>

            <form method="POST" action="{{ route('auth.login') }}" class="space-y-6 stitch-fade-up" style="animation-delay: 0.1s;">
                @csrf
                
                <!-- Login Input -->
                <div class="space-y-2">
                    <label for="login" class="block text-sm font-semibold text-on-surface">Email / Số điện thoại</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                           class="w-full rounded-xl border border-outline-variant px-4 py-3.5 text-base text-on-surface bg-surface focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none" 
                           placeholder="admin@hosty.vn hoặc 0909123456">
                    @error('login')
                        <p class="text-error text-xs font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-sm font-semibold text-on-surface">Mật khẩu</label>
                        <a href="#" class="text-sm font-medium text-primary hover:text-primary-fixed-variant transition-colors">Quên mật khẩu?</a>
                    </div>
                    <input id="password" name="password" type="password" required 
                           class="w-full rounded-xl border border-outline-variant px-4 py-3.5 text-base text-on-surface bg-surface focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none">
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer">
                    <label for="remember" class="ml-3 block text-sm text-on-surface-variant cursor-pointer select-none">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                
                <!-- Submit -->
                <button type="submit" 
                        class="w-full bg-primary text-on-primary py-3.5 rounded-full font-bold transition-all hover:bg-primary-fixed-variant hover:shadow-md tactile-feedback">
                    Đăng nhập
                </button>
            </form>
            
            <div class="mt-8 text-center text-sm text-on-surface-variant stitch-fade-up" style="animation-delay: 0.2s;">
                <p>Chưa có tài khoản? <a href="#" class="font-bold text-primary hover:text-primary-fixed-variant transition-colors">Đăng ký ngay</a></p>
                <div class="mt-8 p-4 rounded-xl bg-surface-container-low text-xs border border-outline-variant/50 flex flex-col gap-1 text-left">
                    <p class="font-semibold mb-1 text-on-surface">Tài khoản demo:</p>
                    <p>Khách thuê: <strong>0909123456</strong> / <strong>password</strong></p>
                    <p>Admin: <strong>admin@hosty.vn</strong> / <strong>password</strong></p>
                </div>
            </div>
            
            <!-- Back to Home -->
            <div class="mt-8 text-center md:hidden stitch-fade-up" style="animation-delay: 0.3s;">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
