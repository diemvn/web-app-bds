@extends('layouts.public', ['noHeader' => true, 'noFooter' => true])

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

@section('body')
<div class="flex flex-col h-[100dvh] overflow-hidden bg-surface" x-data="mapListings()">
    <!-- Header -->
    <header class="flex-none bg-surface/90 backdrop-blur-md border-b border-outline-variant/30 px-4 md:px-6 h-16 md:h-[72px] flex items-center justify-between z-50">
        <div class="flex items-center gap-4 md:gap-8 flex-1">
            <a href="{{ route('home') }}" class="flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg">
                <x-hosty-logo class="!text-2xl hidden md:block" />
                <span class="material-symbols-outlined text-primary md:hidden">arrow_back</span>
            </a>

            <!-- Search Bar -->
            <div class="flex-1 max-w-md hidden md:flex items-center bg-surface-container-low rounded-full px-4 h-10 border border-outline-variant/50 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all relative">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">search</span>
                <input type="text" x-model="searchQuery" @keydown.enter="searchLocation" placeholder="Tìm địa điểm, khu vực..." class="flex-1 bg-transparent border-none text-sm focus:ring-0 px-2 outline-none text-on-surface">
                <div x-show="isSearching" class="absolute right-4 w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin" style="display: none;"></div>
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
    <div class="hidden md:flex flex-none items-center gap-3 px-6 py-3 border-b border-outline-variant/30 bg-surface z-40 overflow-visible hide-scrollbar relative">
        <!-- Mức giá Filter -->
        <div class="relative" x-data="{ 
            open: false, 
            localMin: 0, 
            localMax: 30000000, 
            maxLimit: 30000000, 
            step: 500000, 
            formatPrice(val) { 
                return val === 0 ? '0 ₫' : (val >= 1000000 ? (val/1000000) + ' triệu' : (val/1000) + 'k'); 
            } 
        }" x-effect="if(!open) { localMin = filters.price_min || 0; localMax = filters.price_max || maxLimit; }">
            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border text-sm font-medium transition-colors tactile-feedback" :class="(filters.price_min || filters.price_max) ? 'bg-primary-container text-on-primary-container border-transparent' : 'border-outline-variant text-on-surface hover:bg-surface-container-low'">
                Mức giá
                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
            </button>
            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-[340px] bg-surface rounded-2xl shadow-lg border border-outline-variant/30 p-5 z-50">
                <div class="flex justify-between text-sm text-on-surface-variant font-medium mb-4">
                    <span x-text="formatPrice(0)"></span>
                    <span x-text="formatPrice(maxLimit) + '+'"></span>
                </div>

                <!-- Slider -->
                <div class="relative h-2 bg-surface-container-high rounded-full mb-8 mx-2">
                    <!-- Active track -->
                    <div class="absolute h-full bg-primary rounded-full" :style="`left: ${(localMin/maxLimit)*100}%; right: ${100 - (localMax/maxLimit)*100}%`"></div>
                    
                    <input type="range" min="0" :max="maxLimit" :step="step" x-model.number="localMin" @input="localMin = Math.min(localMin, localMax - step)" class="absolute w-full -top-2 h-6 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:shadow-md cursor-pointer">
                    
                    <input type="range" min="0" :max="maxLimit" :step="step" x-model.number="localMax" @input="localMax = Math.max(localMax, localMin + step)" class="absolute w-full -top-2 h-6 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:shadow-md cursor-pointer">
                </div>

                <!-- Inputs -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-on-surface-variant block mb-1">Tối thiểu</label>
                        <input type="number" x-model.number="localMin" @blur="localMin = Math.max(0, Math.min(localMin, localMax - step))" class="w-full bg-surface border border-outline-variant rounded-xl text-sm px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                    <span class="text-on-surface-variant font-medium mt-5">-</span>
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-on-surface-variant block mb-1">Tối đa</label>
                        <input type="number" x-model.number="localMax" @blur="localMax = Math.min(maxLimit, Math.max(localMax, localMin + step))" class="w-full bg-surface border border-outline-variant rounded-xl text-sm px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                </div>

                <!-- Apply Button -->
                <button @click="filters.price_min = localMin > 0 ? localMin : null; filters.price_max = localMax < maxLimit ? localMax : null; open = false;" class="w-full bg-primary text-white font-bold py-2.5 rounded-xl hover:bg-primary-fixed-variant transition-colors tactile-feedback">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Diện tích Filter -->
        <div class="relative" x-data="{ 
            open: false, 
            localMin: 0, 
            localMax: 150, 
            maxLimit: 150, 
            step: 5 
        }" x-effect="if(!open) { localMin = filters.area_min || 0; localMax = filters.area_max || maxLimit; }">
            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border text-sm font-medium transition-colors tactile-feedback" :class="(filters.area_min || filters.area_max) ? 'bg-primary-container text-on-primary-container border-transparent' : 'border-outline-variant text-on-surface hover:bg-surface-container-low'">
                Diện tích
                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
            </button>
            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-[340px] bg-surface rounded-2xl shadow-lg border border-outline-variant/30 p-5 z-50">
                <div class="flex justify-between text-sm text-on-surface-variant font-medium mb-4">
                    <span>0 m²</span>
                    <span x-text="maxLimit + ' m²+'"></span>
                </div>

                <!-- Slider -->
                <div class="relative h-2 bg-surface-container-high rounded-full mb-8 mx-2">
                    <div class="absolute h-full bg-primary rounded-full" :style="`left: ${(localMin/maxLimit)*100}%; right: ${100 - (localMax/maxLimit)*100}%`"></div>
                    <input type="range" min="0" :max="maxLimit" :step="step" x-model.number="localMin" @input="localMin = Math.min(localMin, localMax - step)" class="absolute w-full -top-2 h-6 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:shadow-md cursor-pointer">
                    <input type="range" min="0" :max="maxLimit" :step="step" x-model.number="localMax" @input="localMax = Math.max(localMax, localMin + step)" class="absolute w-full -top-2 h-6 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:shadow-md cursor-pointer">
                </div>

                <!-- Inputs -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-on-surface-variant block mb-1">Tối thiểu</label>
                        <div class="relative">
                            <input type="number" x-model.number="localMin" @blur="localMin = Math.max(0, Math.min(localMin, localMax - step))" class="w-full bg-surface border border-outline-variant rounded-xl text-sm px-3 py-2 pr-8 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-on-surface-variant">m²</span>
                        </div>
                    </div>
                    <span class="text-on-surface-variant font-medium mt-5">-</span>
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-on-surface-variant block mb-1">Tối đa</label>
                        <div class="relative">
                            <input type="number" x-model.number="localMax" @blur="localMax = Math.min(maxLimit, Math.max(localMax, localMin + step))" class="w-full bg-surface border border-outline-variant rounded-xl text-sm px-3 py-2 pr-8 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-on-surface-variant">m²</span>
                        </div>
                    </div>
                </div>

                <button @click="filters.area_min = localMin > 0 ? localMin : null; filters.area_max = localMax < maxLimit ? localMax : null; open = false;" class="w-full bg-primary text-white font-bold py-2.5 rounded-xl hover:bg-primary-fixed-variant transition-colors tactile-feedback">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Tiện ích Filter -->
        <div class="relative" x-data="{ 
            open: false, 
            localAmenities: [] 
        }" x-effect="if(!open) { localAmenities = [...filters.amenities]; }">
            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border text-sm font-medium transition-colors tactile-feedback" :class="(filters.amenities.length > 0) ? 'bg-primary-container text-on-primary-container border-transparent' : 'border-outline-variant text-on-surface hover:bg-surface-container-low'">
                Tiện ích <span x-show="filters.amenities.length > 0" x-text="`(${filters.amenities.length})`"></span>
                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
            </button>
            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-72 bg-surface rounded-2xl shadow-lg border border-outline-variant/30 p-5 z-50">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="localAmenities" value="máy lạnh" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline">
                        <span class="text-sm text-on-surface">Máy lạnh</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="localAmenities" value="nóng lạnh" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline">
                        <span class="text-sm text-on-surface">Nước nóng</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="localAmenities" value="máy giặt riêng" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline">
                        <span class="text-sm text-on-surface">Máy giặt</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="localAmenities" value="bếp" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline">
                        <span class="text-sm text-on-surface">Bếp</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="localAmenities" value="giường nệm" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline">
                        <span class="text-sm text-on-surface">Giường nệm</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="localAmenities" value="tủ lạnh" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline">
                        <span class="text-sm text-on-surface">Tủ lạnh</span>
                    </label>
                </div>
                
                <button @click="filters.amenities = [...localAmenities]; open = false;" class="w-full bg-primary text-white font-bold py-2.5 rounded-xl hover:bg-primary-fixed-variant transition-colors tactile-feedback">
                    Áp dụng
                </button>
            </div>
        </div>

        <button @click="clearFilters()" x-show="filters.price_min || filters.price_max || filters.area_min || filters.area_max || filters.amenities.length > 0" class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-error/50 text-error text-sm font-medium hover:bg-error-container transition-colors tactile-feedback">
            <span class="material-symbols-outlined text-[18px]">close</span>
            Xóa lọc
        </button>
        
        <button x-show="!(filters.price_min || filters.price_max || filters.area_min || filters.area_max || filters.amenities.length > 0)" class="flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-outline-variant text-sm font-medium text-on-surface hover:bg-surface-container-low transition-colors tactile-feedback">
            <span class="material-symbols-outlined text-[18px]">tune</span>
            Thêm bộ lọc
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:flex-row overflow-hidden relative">
        
        <!-- Map View -->
        <!-- Mobile: 45vh height. Desktop: flex-1/flexible -->
        <div class="w-full h-[45vh] md:h-full md:flex-1 lg:flex-[1.5] relative bg-surface-container-low z-10 flex-none" id="map-container">
            <div id="map" class="absolute inset-0 z-0"></div>
            
            <!-- Map Controls -->
            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-10">
                <button type="button" @click="fetchListings()" class="bg-surface/90 backdrop-blur-md px-6 py-2.5 rounded-full shadow-sm border border-outline-variant/50 text-sm font-semibold text-primary hover:bg-surface transition-colors flex items-center gap-2 tactile-feedback">
                    <span class="material-symbols-outlined text-[18px]" :class="{ 'animate-spin': isLoading }">refresh</span>
                    Tìm trong khu vực này
                </button>
            </div>
            
            <div class="absolute bottom-[80px] md:bottom-6 right-4 z-10 flex flex-col gap-2">
                <div class="bg-surface/90 backdrop-blur-md rounded-xl shadow-sm border border-outline-variant/50 overflow-hidden flex flex-col">
                    <button type="button" onclick="map.zoomIn()" class="p-3 text-on-surface-variant hover:text-primary hover:bg-surface-container-low border-b border-outline-variant/30 transition-colors tactile-feedback">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                    </button>
                    <button type="button" onclick="map.zoomOut()" class="p-3 text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors tactile-feedback">
                        <span class="material-symbols-outlined text-[20px]">remove</span>
                    </button>
                </div>
                <button type="button" @click="myLocation()" class="bg-surface/90 backdrop-blur-md p-3 rounded-xl shadow-sm border border-outline-variant/50 text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors tactile-feedback">
                    <span class="material-symbols-outlined text-[20px]">my_location</span>
                </button>
            </div>
        </div>

        <!-- List View -->
        <!-- Mobile: 55vh height, bottom sheet style. Desktop: fixed width, full height -->
        <div class="w-full h-[55vh] md:h-full md:w-[450px] lg:w-[600px] bg-surface border-t md:border-t-0 md:border-l border-outline-variant/30 flex flex-col z-20 shadow-[0_-8px_24px_rgba(0,0,0,0.12)] md:shadow-none -mt-4 md:mt-0 rounded-t-2xl md:rounded-none relative transition-transform duration-300" id="list-container" :class="mobileListCollapsed ? 'translate-y-[calc(100%-60px)] md:translate-y-0' : 'translate-y-0'">
            <!-- Mobile handle -->
            <div class="md:hidden flex justify-center py-3 cursor-pointer tactile-feedback" @click="mobileListCollapsed = !mobileListCollapsed">
                <div class="w-12 h-1.5 bg-outline-variant/50 rounded-full"></div>
            </div>
            
            <!-- List Header -->
            <div class="px-4 py-3 md:py-4 border-b border-outline-variant/30 flex items-center justify-between bg-surface sticky top-0 z-10">
                <h2 class="font-bold text-on-surface" x-text="`${totalListings} phòng phù hợp`">{{ $total ?? count($listings) }} phòng phù hợp</h2>
                <button class="flex items-center gap-1 text-sm font-medium text-on-surface-variant hover:text-primary transition-colors">
                    Sắp xếp <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
                </button>
            </div>
            
            <!-- Listings -->
            <div class="flex-1 overflow-y-auto p-4 md:p-5 hide-scrollbar bg-surface-container-lowest md:bg-surface relative">
                <!-- Loader -->
                <div x-show="isLoading" style="display: none;" class="absolute inset-0 bg-surface/50 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 pb-24 md:pb-5" id="listings-grid">
                    @include('public.partials.map-listings', ['listings' => $listings])
                </div>
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
    let map = null;
    let markersCluster = null;

    document.addEventListener('alpine:init', () => {
        Alpine.data('mapListings', () => ({
            mobileListCollapsed: false,
            isLoading: false,
            isSearching: false,
            searchQuery: '',
            totalListings: {{ $total ?? count($listings) }},
            filters: {
                price_min: null,
                price_max: null,
                area_min: null,
                area_max: null,
                amenities: []
            },
            
            init() {
                this.initMap();
                
                // Watch filters change
                this.$watch('filters', () => {
                    this.fetchListings();
                }, { deep: true });
            },

            initMap() {
                map = L.map('map', {
                    zoomControl: false,
                }).setView([10.7981, 106.7165], 13);

                // Use Google Maps tile layer for strict Vietnamese sovereignty labels (Hoàng Sa, Trường Sa)
                L.tileLayer('https://mt1.google.com/vt/lyrs=m&hl=vi&x={x}&y={y}&z={z}', {
                    attribution: '&copy; Google Maps',
                    maxZoom: 19,
                }).addTo(map);

                markersCluster = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 40,
                    iconCreateFunction: function (cluster) {
                        return L.divIcon({
                            html: `<div class="bg-primary text-white font-bold rounded-full w-10 h-10 flex items-center justify-center shadow-md border-2 border-white">${cluster.getChildCount()}</div>`,
                            className: 'custom-cluster-icon',
                            iconSize: L.point(40, 40)
                        });
                    }
                });

                map.addLayer(markersCluster);
                
                // Add initial markers from the DB
                const initialData = @json($listings);
                this.updateMarkers(initialData);

                // Auto search by location
                this.myLocation();
            },

            async fetchListings() {
                this.isLoading = true;
                const bounds = map.getBounds();
                
                const params = new URLSearchParams({
                    swLat: bounds.getSouthWest().lat,
                    swLng: bounds.getSouthWest().lng,
                    neLat: bounds.getNorthEast().lat,
                    neLng: bounds.getNorthEast().lng,
                });

                if (this.filters.price_min) params.append('price_min', this.filters.price_min);
                if (this.filters.price_max) params.append('price_max', this.filters.price_max);
                if (this.filters.area_min) params.append('area_min', this.filters.area_min);
                if (this.filters.area_max) params.append('area_max', this.filters.area_max);
                
                this.filters.amenities.forEach(a => params.append('amenities[]', a));

                try {
                    const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    this.totalListings = data.total;
                    document.getElementById('listings-grid').innerHTML = data.html;
                    this.updateMarkers(data.listings);
                    
                } catch (error) {
                    console.error('Error fetching listings:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            updateMarkers(listings) {
                markersCluster.clearLayers();
                
                listings.forEach(listing => {
                    if (listing.lat && listing.lng) {
                        const priceFmt = (listing.price / 1000000).toLocaleString('vi-VN') + ' tr';
                        const iconHtml = `<div class="bg-surface px-2.5 py-1 rounded-full shadow-[0_2px_8px_rgba(0,0,0,0.12)] border border-outline-variant font-bold text-sm text-on-surface whitespace-nowrap hover:bg-primary hover:text-white hover:border-primary transition-colors cursor-pointer select-none">${priceFmt}</div>`;
                        
                        const icon = L.divIcon({
                            html: iconHtml,
                            className: 'custom-marker',
                            iconSize: [null, null],
                            iconAnchor: [20, 20] // Approximate center
                        });

                        const marker = L.marker([listing.lat, listing.lng], { icon });
                        marker.on('click', () => {
                            // Focus or scroll list
                        });
                        markersCluster.addLayer(marker);
                    }
                });
            },

            async searchLocation() {
                if (!this.searchQuery.trim()) return;
                
                this.isSearching = true;
                try {
                    // Search via OpenStreetMap Nominatim
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&countrycodes=vn&limit=1`);
                    const data = await response.json();
                    
                    if (data && data.length > 0) {
                        const place = data[0];
                        map.setView([place.lat, place.lon], 13, { animate: true });
                        setTimeout(() => this.fetchListings(), 500);
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isSearching = false;
                }
            },

            myLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        map.setView([position.coords.latitude, position.coords.longitude], 14, { animate: true });
                        setTimeout(() => this.fetchListings(), 500);
                    });
                }
            },
            
            clearFilters() {
                this.filters = {
                    price_min: null, price_max: null, area_min: null, area_max: null, amenities: []
                };
            }
        }));
    });
</script>
@endpush
