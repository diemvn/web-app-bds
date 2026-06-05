<header class="sticky top-0 z-50 w-full bg-surface/90 backdrop-blur-md border-b border-outline-variant/30" x-data="{ mobileMenuOpen: false }">
    <div class="taste-container h-16 md:h-[72px] flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg">
            <x-hosty-logo class="!text-2xl" />
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-1 xl:gap-2" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ Route::is('home') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                Trang chủ
            </a>
            <a href="{{ route('map.index') }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ Route::is('map.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                Tìm trọ
            </a>
            <a href="{{ Route::has('news.index') ? route('news.index') : '/tin-tuc' }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ Route::is('news.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                Tin tức
            </a>
            <a href="{{ Route::has('support.index') ? route('support.index') : '/ho-tro' }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ Route::is('support.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                Hỗ trợ
            </a>
            <a href="{{ Route::has('favorites.index') ? route('favorites.index') : '/yeu-thich' }}" class="px-4 py-2 rounded-full text-sm font-medium transition-colors flex items-center gap-1.5 {{ Route::is('favorites.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[20px]" x-data="{ filled: false }">favorite</span>
                Yêu thích
                <span x-data x-show="$store.favorites && $store.favorites.count > 0" x-text="$store.favorites.count" class="bg-error text-on-error text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-cloak></span>
            </a>
        </nav>

        <!-- Auth / CTA Desktop -->
        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ auth()->user()->isTenant() ? route('tenant.home') : '/admin' }}" class="flex items-center gap-2 px-4 py-2 rounded-full border border-outline-variant hover:bg-surface-container-low transition-colors text-sm font-medium text-on-surface tactile-feedback">
                    <span class="material-symbols-outlined text-[20px]">account_circle</span>
                    {{ auth()->user()->name ?? 'Tài khoản' }}
                </a>
            @else
                <a href="{{ route('auth.login') ?? '/dang-nhap' }}" class="px-6 py-2.5 rounded-full bg-primary text-on-primary text-sm font-medium transition-all hover:bg-primary-fixed-variant hover:shadow-md tactile-feedback">
                    Đăng nhập
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Toggle -->
        <button type="button" @click="mobileMenuOpen = true" class="md:hidden p-2 text-on-surface-variant rounded-full hover:bg-surface-container-low focus:outline-none" aria-label="Open menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-[100] md:hidden">
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-inverse-surface/40 backdrop-blur-sm" 
             @click="mobileMenuOpen = false"></div>
             
        <!-- Panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="fixed inset-y-0 left-0 w-4/5 max-w-sm bg-surface shadow-2xl flex flex-col">
             
            <div class="px-4 h-16 flex items-center justify-between border-b border-outline-variant/30">
                <x-hosty-logo class="!text-xl" />
                <button type="button" @click="mobileMenuOpen = false" class="p-2 text-on-surface-variant rounded-full hover:bg-surface-container-low" aria-label="Close menu">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-3 flex flex-col gap-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium {{ Route::is('home') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">home</span> Trang chủ
                </a>
                <a href="{{ route('map.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium {{ Route::is('map.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">map</span> Tìm trọ
                </a>
                <a href="{{ Route::has('news.index') ? route('news.index') : '/tin-tuc' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium {{ Route::is('news.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">newspaper</span> Tin tức
                </a>
                <a href="{{ Route::has('support.index') ? route('support.index') : '/ho-tro' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium {{ Route::is('support.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">help</span> Hỗ trợ
                </a>
                <a href="{{ Route::has('favorites.index') ? route('favorites.index') : '/yeu-thich' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium {{ Route::is('favorites.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined">favorite</span> Yêu thích
                    <span x-data x-show="$store.favorites && $store.favorites.count > 0" x-text="$store.favorites.count" class="ml-auto bg-error text-on-error text-xs font-bold px-2 py-0.5 rounded-full" x-cloak></span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-outline-variant/30">
                @auth
                    <div class="flex items-center gap-3 mb-4 px-2">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface">{{ auth()->user()->name ?? 'Tài khoản' }}</p>
                            <p class="text-xs text-on-surface-variant">{{ auth()->user()->phone }}</p>
                        </div>
                    </div>
                    <a href="{{ auth()->user()->isTenant() ? route('tenant.home') : '/admin' }}" class="flex w-full justify-center items-center gap-2 px-4 py-3 rounded-xl border border-outline hover:bg-surface-container-low transition-colors text-sm font-medium text-on-surface">
                        Vào trang quản lý
                    </a>
                @else
                    <a href="{{ route('auth.login') ?? '/dang-nhap' }}" class="flex w-full justify-center items-center px-4 py-3 rounded-xl bg-primary text-on-primary text-sm font-medium transition-colors hover:bg-primary-fixed-variant">
                        Đăng nhập
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
