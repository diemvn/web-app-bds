@props(['active' => 'home'])

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-2 pb-safe bg-surface dark:bg-background h-20 shadow-[0px_-4px_12px_rgba(0,0,0,0.05)] rounded-t-xl">
<a class="flex flex-col items-center justify-center transition-all duration-200 {{ $active === 'home' ? 'text-secondary dark:text-secondary-fixed-dim bg-secondary-fixed dark:bg-on-secondary-fixed-variant' : 'text-on-surface-variant dark:text-outline hover:text-primary' }} rounded-full px-4 py-1 scale-90" href="{{ route('home') }}">
<span class="material-symbols-outlined" data-icon="home" @if($active === 'home') style="font-variation-settings: 'FILL' 1;" @endif>home</span>
<span class="text-label-sm font-label-sm">Trang chủ</span>
</a>
<a class="flex flex-col items-center justify-center {{ $active === 'saved' ? 'text-secondary bg-secondary-fixed' : 'text-on-surface-variant dark:text-outline' }} px-4 py-1 hover:text-primary dark:hover:text-primary-fixed transition-colors" href="#">
<span class="material-symbols-outlined" data-icon="favorite">favorite</span>
<span class="text-label-sm font-label-sm">Đã lưu</span>
</a>
<a class="flex flex-col items-center justify-center {{ $active === 'notify' ? 'text-secondary bg-secondary-fixed' : 'text-on-surface-variant dark:text-outline' }} px-4 py-1 hover:text-primary dark:hover:text-primary-fixed transition-colors" href="{{ route('tenant.login') }}">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="text-label-sm font-label-sm">Thông báo</span>
</a>
<a class="flex flex-col items-center justify-center {{ $active === 'profile' ? 'text-secondary bg-secondary-fixed' : 'text-on-surface-variant dark:text-outline' }} px-4 py-1 hover:text-primary dark:hover:text-primary-fixed transition-colors" href="{{ route('tenant.login') }}">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="text-label-sm font-label-sm">Cá nhân</span>
</a>
</nav>
