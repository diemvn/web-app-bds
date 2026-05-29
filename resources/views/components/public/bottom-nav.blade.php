@props(['active' => 'home'])

<nav class="fixed bottom-0 inset-x-0 z-50 bg-white border-t border-slate-200 pb-safe md:hidden">
    <div class="flex justify-around items-center h-16 max-w-lg mx-auto">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ $active === 'home' ? 'text-[var(--ml-primary)] font-semibold' : 'text-slate-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Trang chủ
        </a>
        <a href="{{ route('map.index') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ $active === 'map' ? 'text-[var(--ml-primary)] font-semibold' : 'text-slate-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            Bản đồ
        </a>
        <a href="{{ route('tenant.login') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ $active === 'notify' ? 'text-[var(--ml-primary)] font-semibold' : 'text-slate-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Thông báo
        </a>
        <a href="{{ route('tenant.login') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ $active === 'profile' ? 'text-[var(--ml-primary)] font-semibold' : 'text-slate-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Tài khoản
        </a>
    </div>
</nav>
