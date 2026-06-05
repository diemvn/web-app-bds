@props(['active' => 'home'])

<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-2 pb-safe bg-surface/90 backdrop-blur-md h-[68px] shadow-[0px_-4px_16px_rgba(0,0,0,0.06)] border-t border-outline-variant/30">
    <a class="flex flex-col items-center justify-center transition-all duration-200 {{ $active === 'home' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} px-4 py-1 tactile-feedback" href="{{ route('home') }}">
        <span class="material-symbols-outlined text-[24px]" @if($active === 'home') style="font-variation-settings: 'FILL' 1;" @endif>home</span>
        <span class="text-[10px] font-medium mt-1">Trang chủ</span>
    </a>
    
    <a class="flex flex-col items-center justify-center transition-all duration-200 {{ $active === 'map' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} px-4 py-1 tactile-feedback" href="{{ route('map.index') }}">
        <span class="material-symbols-outlined text-[24px]" @if($active === 'map') style="font-variation-settings: 'FILL' 1;" @endif>map</span>
        <span class="text-[10px] font-medium mt-1">Tìm trọ</span>
    </a>
    
    <a class="flex flex-col items-center justify-center transition-all duration-200 {{ $active === 'news' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} px-4 py-1 tactile-feedback" href="{{ Route::has('news.index') ? route('news.index') : '/tin-tuc' }}">
        <span class="material-symbols-outlined text-[24px]" @if($active === 'news') style="font-variation-settings: 'FILL' 1;" @endif>newspaper</span>
        <span class="text-[10px] font-medium mt-1">Tin tức</span>
    </a>
    
    <a class="flex flex-col items-center justify-center transition-all duration-200 {{ $active === 'favorites' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} px-4 py-1 relative tactile-feedback" href="{{ Route::has('favorites.index') ? route('favorites.index') : '/yeu-thich' }}">
        <span class="material-symbols-outlined text-[24px]" @if($active === 'favorites') style="font-variation-settings: 'FILL' 1;" @endif>favorite</span>
        <span class="text-[10px] font-medium mt-1">Yêu thích</span>
        <span x-data x-show="$store.favorites && $store.favorites.count > 0" x-text="$store.favorites.count" class="absolute top-0 right-2 bg-error text-on-error text-[9px] font-bold px-1.5 py-0.5 rounded-full" x-cloak></span>
    </a>
    
    <a class="flex flex-col items-center justify-center transition-all duration-200 {{ $active === 'profile' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} px-4 py-1 tactile-feedback" href="{{ route('auth.login') ?? '/dang-nhap' }}">
        <span class="material-symbols-outlined text-[24px]" @if($active === 'profile') style="font-variation-settings: 'FILL' 1;" @endif>person</span>
        <span class="text-[10px] font-medium mt-1">Tài khoản</span>
    </a>
</nav>
