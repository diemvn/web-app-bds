@php
    $branding = \App\Models\SystemSetting::branding();
@endphp

<footer class="bg-surface-container-lowest border-t border-outline-variant/30 mt-auto">
    <div class="taste-container taste-section py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
            
            <!-- Về HOSTY -->
            <div class="space-y-4">
                <x-hosty-logo class="!text-2xl" />
                <p class="text-sm text-on-surface-variant leading-relaxed mt-4 max-w-sm">
                    {{ $branding['tagline'] ?? 'Hệ thống quản lý phòng trọ hiện đại, giúp bạn tìm kiếm không gian sống lý tưởng một cách dễ dàng và nhanh chóng.' }}
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="font-bold text-lg">f</span>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="font-bold text-lg">Z</span>
                    </a>
                </div>
            </div>

            <!-- Liên kết nhanh -->
            <div>
                <h3 class="font-bold text-on-surface mb-6">Liên kết nhanh</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="text-on-surface-variant hover:text-primary transition-colors">Trang chủ</a></li>
                    <li><a href="{{ route('map.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">Tìm trọ</a></li>
                    <li><a href="{{ Route::has('news.index') ? route('news.index') : '/tin-tuc' }}" class="text-on-surface-variant hover:text-primary transition-colors">Tin tức & Cẩm nang</a></li>
                    <li><a href="{{ Route::has('favorites.index') ? route('favorites.index') : '/yeu-thich' }}" class="text-on-surface-variant hover:text-primary transition-colors">Phòng đã lưu</a></li>
                </ul>
            </div>

            <!-- Hỗ trợ -->
            <div>
                <h3 class="font-bold text-on-surface mb-6">Hỗ trợ khách hàng</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ Route::has('support.index') ? route('support.index') : '/ho-tro' }}" class="text-on-surface-variant hover:text-primary transition-colors">Trung tâm hỗ trợ</a></li>
                    <li><a href="{{ Route::has('support.index') ? route('support.index') : '/ho-tro' }}#faq" class="text-on-surface-variant hover:text-primary transition-colors">Câu hỏi thường gặp</a></li>
                    <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Hướng dẫn thuê phòng</a></li>
                    <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Chính sách bảo mật</a></li>
                </ul>
            </div>

            <!-- Liên hệ -->
            <div>
                <h3 class="font-bold text-on-surface mb-6">Liên hệ</h3>
                <ul class="space-y-4 text-sm text-on-surface-variant">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
                        <span>TP. Hồ Chí Minh, Việt Nam</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">call</span>
                        <a href="tel:0909123456" class="hover:text-primary transition-colors">0909 123 456</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">mail</span>
                        <a href="mailto:support@hosty.vn" class="hover:text-primary transition-colors">support@hosty.vn</a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="mt-12 pt-8 border-t border-outline-variant/30 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-on-surface-variant">
            <p>&copy; {{ date('Y') }} {{ $branding['app_name'] ?? 'HOSTY' }}. Tất cả quyền được bảo lưu.</p>
            <p>Thiết kế với <span class="text-error">♥</span> tại Việt Nam</p>
        </div>
    </div>
</footer>
