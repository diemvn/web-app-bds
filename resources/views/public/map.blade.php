@extends('layouts.public', ['noHeader' => true, 'noFooter' => true])

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

@section('body')
<div class="flex flex-col h-screen overflow-hidden bg-surface">
    <!-- Header -->
    <header class="flex-none bg-surface/90 backdrop-blur-md border-b border-outline-variant/30 px-4 md:px-6 h-16 md:h-[72px] flex items-center justify-between z-50">
        <div class="flex items-center gap-4 md:gap-8 flex-1">
            <a href="{{ route('home') }}" class="flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg">
                <x-hosty-logo class="!text-2xl hidden md:block" />
                <span class="material-symbols-outlined text-primary md:hidden">arrow_back</span>
            </a>

            <!-- Search Bar -->
            <div class="flex-1 max-w-md hidden md:flex items-center bg-surface-container-low rounded-full px-4 h-10 border border-outline-variant/50 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">search</span>
                <input type="text" placeholder="Tìm địa điểm, khu vực..." class="flex-1 bg-transparent border-none text-sm focus:ring-0 px-2 outline-none text-on-surface">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button class="md:hidden p-2 text-on-surface-variant rounded-full hover:bg-surface-container-low focus:outline-none tactile-feedback">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="md:hidden p-2 text-on-surface-variant rounded-full hover:bg-surface-container-low focus:outline-none tactile-feedback">
                <span class="material-symbols-outlined">tune</span>
            </button>
            <div class="hidden md:flex items-center gap-2">
                @auth
                    <a href="{{ auth()->user()->isTenant() ? route('tenant.home') : '/admin' }}" class="px-4 py-2 text-sm font-medium text-on-surface hover:bg-surface-container-low rounded-full transition-colors tactile-feedback">Quản lý</a>
                @else
                    <a href="{{ route('auth.login') }}" class="px-4 py-2 text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors">Đăng nhập</a>
                @endauth
                <a href="#" class="px-4 py-2.5 rounded-full bg-primary text-on-primary text-sm font-medium transition-colors hover:bg-primary-fixed-variant tactile-feedback">Đăng tin</a>
            </div>
        </div>
    </header>

    <!-- Filters Bar (Desktop) -->
    <div class="hidden md:flex flex-none items-center gap-3 px-6 py-3 border-b border-outline-variant/30 bg-surface z-40 overflow-x-auto hide-scrollbar">
        <button class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">
            Mức giá
            <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
        </button>
        <button class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">
            Loại phòng
            <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
        </button>
        <button class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">
            Phòng ngủ
            <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
        </button>
        <button class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">
            <span class="material-symbols-outlined text-[18px]">tune</span>
            Thêm bộ lọc
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:flex-row overflow-hidden relative">
        
        <!-- Map View (Always visible on desktop, toggleable on mobile) -->
        <div class="flex-1 md:flex-[1.5] lg:flex-[2] relative bg-surface-container-low h-full z-10" id="map-container">
            <div id="map" class="absolute inset-0 z-0"></div>
            
            <!-- Map Controls -->
            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-10">
                <button type="button" class="bg-surface/90 backdrop-blur-md px-6 py-2.5 rounded-full shadow-sm border border-outline-variant/50 text-sm font-semibold text-primary hover:bg-surface transition-colors flex items-center gap-2 tactile-feedback">
                    <span class="material-symbols-outlined text-[18px]">refresh</span>
                    Tìm trong khu vực này
                </button>
            </div>
            
            <div class="absolute bottom-6 right-4 z-10 flex flex-col gap-2">
                <div class="bg-surface/90 backdrop-blur-md rounded-xl shadow-sm border border-outline-variant/50 overflow-hidden flex flex-col">
                    <button type="button" class="p-3 text-on-surface-variant hover:text-primary hover:bg-surface-container-low border-b border-outline-variant/30 transition-colors tactile-feedback">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                    </button>
                    <button type="button" class="p-3 text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors tactile-feedback">
                        <span class="material-symbols-outlined text-[20px]">remove</span>
                    </button>
                </div>
                <button type="button" class="bg-surface/90 backdrop-blur-md p-3 rounded-xl shadow-sm border border-outline-variant/50 text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors tactile-feedback">
                    <span class="material-symbols-outlined text-[20px]">my_location</span>
                </button>
            </div>
        </div>

        <!-- List View -->
        <div class="w-full md:w-[400px] lg:w-[450px] h-1/2 md:h-full bg-surface border-t md:border-t-0 md:border-l border-outline-variant/30 flex flex-col z-20 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] md:shadow-none transition-transform duration-300 transform translate-y-0" id="list-container">
            <!-- Mobile handle -->
            <div class="md:hidden flex justify-center py-2 cursor-pointer tactile-feedback" onclick="toggleMobileList()">
                <div class="w-12 h-1.5 bg-outline-variant rounded-full"></div>
            </div>
            
            <!-- List Header -->
            <div class="px-4 py-3 border-b border-outline-variant/30 flex items-center justify-between bg-surface sticky top-0 z-10">
                <h2 class="font-bold text-on-surface">{{ $total ?? count($listings) }} phòng phù hợp</h2>
                <button class="flex items-center gap-1 text-sm font-medium text-on-surface-variant hover:text-primary transition-colors">
                    Sắp xếp <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
                </button>
            </div>
            
            <!-- Listings -->
            <div class="flex-1 overflow-y-auto p-4 md:p-5 space-y-5 pb-24 md:pb-5 hide-scrollbar bg-surface-container-lowest md:bg-surface">
                @forelse($listings as $listing)
                    <x-stitch.listing-card :listing="$listing" />
                @empty
                    <div class="py-12 text-center text-on-surface-variant bg-surface-container-lowest rounded-2xl border border-outline-variant border-dashed">
                        <span class="material-symbols-outlined text-[48px] opacity-50 mb-4">search_off</span>
                        <p>Không tìm thấy phòng nào trong khu vực này.</p>
                        <button class="mt-4 px-4 py-2 rounded-full border border-outline-variant text-sm font-medium hover:bg-surface-container-low transition-colors tactile-feedback">Xóa bộ lọc</button>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
    
    <!-- Mobile Bottom Nav -->
    <div class="md:hidden">
        <x-stitch.bottom-nav active="map" />
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
    function toggleMobileList() {
        const list = document.getElementById('list-container');
        if (list.classList.contains('translate-y-0')) {
            // Collapse
            list.style.transform = 'translateY(calc(100% - 60px))';
            list.classList.remove('translate-y-0');
            list.classList.add('translate-y-collapsed');
        } else {
            // Expand
            list.style.transform = 'translateY(0)';
            list.classList.add('translate-y-0');
            list.classList.remove('translate-y-collapsed');
        }
    }
</script>
@endpush
