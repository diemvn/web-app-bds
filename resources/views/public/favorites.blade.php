@extends('layouts.public')

@section('title', 'Phòng đã lưu - '.($branding['app_name'] ?? 'HOSTY'))

@section('body_class', 'bg-surface text-on-surface font-body-md min-h-screen flex flex-col pb-20 md:pb-0')

@section('body')
<div class="pt-20 md:pt-28 pb-8 bg-surface border-b border-outline-variant/30 sticky top-0 z-40">
    <div class="taste-container flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-on-surface mb-2">Phòng đã lưu</h1>
            <p class="text-on-surface-variant">Danh sách các phòng trọ bạn đã yêu thích</p>
        </div>
        
        @guest
        <div class="hidden md:block">
            <a href="{{ route('auth.login') }}" class="px-6 py-2.5 rounded-full bg-primary-container text-on-primary-container font-medium hover:bg-primary hover:text-on-primary transition-colors">
                Đăng nhập để lưu vĩnh viễn
            </a>
        </div>
        @endguest
    </div>
</div>

<div class="taste-container py-12 flex-1" x-data="favoritesPage">
    @guest
        <div x-show="!isLoaded" class="py-20 text-center">
            <span class="material-symbols-outlined text-[48px] text-outline-variant animate-spin">refresh</span>
            <p class="mt-4 text-on-surface-variant">Đang tải phòng đã lưu...</p>
        </div>

        <div x-show="isLoaded && $store.favorites.items.length === 0" style="display: none;" class="py-20 text-center bg-surface-container-lowest rounded-3xl border border-outline-variant/50 border-dashed">
            <div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant">favorite_border</span>
            </div>
            <h2 class="text-2xl font-bold text-on-surface mb-2">Bạn chưa lưu phòng nào</h2>
            <p class="text-on-surface-variant max-w-md mx-auto mb-8">Hãy thả tim những phòng trọ bạn thích để xem lại sau. Đăng nhập để lưu trên mọi thiết bị.</p>
            <a href="{{ route('map.index') }}" class="px-8 py-3.5 bg-primary text-on-primary rounded-full font-bold hover:bg-primary-fixed-variant transition-colors tactile-feedback">
                Khám phá phòng trọ
            </a>
        </div>
    @else
        @if($listings->isEmpty())
            <div class="py-20 text-center bg-surface-container-lowest rounded-3xl border border-outline-variant/50 border-dashed">
                <div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant">favorite_border</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface mb-2">Bạn chưa lưu phòng nào</h2>
                <p class="text-on-surface-variant max-w-md mx-auto mb-8">Hãy thả tim những phòng trọ bạn thích để xem lại một cách dễ dàng.</p>
                <a href="{{ route('map.index') }}" class="px-8 py-3.5 bg-primary text-on-primary rounded-full font-bold hover:bg-primary-fixed-variant transition-colors tactile-feedback">
                    Khám phá phòng trọ
                </a>
            </div>
        @endif
    @endguest

    <!-- Listings Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8" 
         @guest x-show="isLoaded && $store.favorites.items.length > 0" style="display: none;" @endguest>
        
        @auth
            @foreach($listings as $listing)
                <div x-data="{ removed: false }" x-show="!removed" class="relative group">
                    <x-stitch.listing-card :listing="$listing" />
                    <!-- Override heart button behavior for favorites page to remove item from view -->
                    <button @click.prevent.stop="$store.favorites.toggle({{ $listing->id }}); removed = true" 
                            class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-surface/90 backdrop-blur-md flex items-center justify-center shadow-sm text-primary hover:bg-surface transition-colors tactile-feedback">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">favorite</span>
                    </button>
                </div>
            @endforeach
        @endauth
        
        @guest
            <template x-for="listing in fetchedListings" :key="listing.id">
                <div x-data="{ removed: false }" x-show="!removed" class="relative group">
                    <!-- Basic card structure mapped from JS for guests -->
                    <a :href="'/phong/' + listing.slug" class="block bg-surface-container-lowest rounded-2xl overflow-hidden border border-outline-variant shadow-sm hover:shadow-md transition-shadow relative">
                        <div class="relative w-full aspect-[4/3] bg-surface-container">
                            <img :src="'/storage/' + listing.image" :alt="listing.title" class="w-full h-full object-cover">
                            
                            <div class="absolute bottom-3 left-3 bg-surface/90 backdrop-blur-md px-3 py-1.5 rounded-full shadow-sm text-on-surface font-bold">
                                <span x-text="new Intl.NumberFormat('vi-VN').format(listing.price)"></span>đ <span class="text-xs text-on-surface-variant font-medium">/tháng</span>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <div class="flex items-center gap-1 text-on-surface-variant text-xs font-medium mb-2">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                <span class="truncate" x-text="listing.location"></span>
                            </div>
                            
                            <h3 class="text-base font-bold text-on-surface mb-2 line-clamp-1 group-hover:text-primary transition-colors" x-text="listing.title"></h3>
                            
                            <div class="flex items-center gap-3 text-sm text-on-surface-variant font-medium">
                                <div class="flex items-center gap-1" x-show="listing.area">
                                    <span class="material-symbols-outlined text-[16px]">square_foot</span>
                                    <span x-text="listing.area + ' m²'"></span>
                                </div>
                                <div class="flex items-center gap-1" x-show="listing.bedrooms">
                                    <span class="material-symbols-outlined text-[16px]">bed</span>
                                    <span x-text="listing.bedrooms"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <button @click.prevent.stop="$store.favorites.toggle(listing.id); removed = true" 
                            class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-surface/90 backdrop-blur-md flex items-center justify-center shadow-sm text-primary hover:bg-surface transition-colors tactile-feedback">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">favorite</span>
                    </button>
                </div>
            </template>
        @endguest
    </div>

    @auth
        @if($listings->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $listings->links() }}
            </div>
        @endif
    @endauth
</div>

@guest
    <div class="md:hidden p-4 bg-surface border-t border-outline-variant/30 sticky bottom-[72px] z-40">
        <a href="{{ route('auth.login') }}" class="w-full flex justify-center items-center py-3 bg-primary-container text-on-primary-container rounded-xl font-medium">
            Đăng nhập để đồng bộ yêu thích
        </a>
    </div>
@endguest

<div class="md:hidden">
    <x-stitch.bottom-nav active="favorites" />
</div>
@endsection

@guest
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('favoritesPage', () => ({
            isLoaded: false,
            fetchedListings: [],
            
            async init() {
                // Fetch guest favorite listings via API based on localStorage
                const favIds = this.$store.favorites.items;
                
                if (favIds.length === 0) {
                    this.isLoaded = true;
                    return;
                }
                
                try {
                    // Quick fetch from server using an API endpoint or using the current route with JSON response
                    const response = await fetch(`/api/listings/by-ids?ids=${favIds.join(',')}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.fetchedListings = data.data || [];
                    }
                } catch (error) {
                    console.error('Failed to load favorites', error);
                } finally {
                    this.isLoaded = true;
                }
            }
        }));
    });
</script>
@endpush
@endguest
