<?php

namespace App\Support;

use App\Models\Listing;
use App\Models\SystemSetting;
use App\Services\BrandingService;
use Illuminate\Support\Str;

class SeoData
{
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public ?string $image = null,
        public string $type = 'website',
        public string $robots = 'index, follow',
        public ?string $siteName = null,
    ) {}

    public static function branding(): array
    {
        return app(BrandingService::class)->all();
    }

    public static function absoluteUrl(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url($url);
    }

    public static function defaultDescription(?array $branding = null): string
    {
        $branding = $branding ?? static::branding();
        $desc = $branding['meta_description_default'] ?? '';

        if (filled($desc)) {
            return static::truncate(strip_tags((string) $desc), 160);
        }

        $tagline = $branding['tagline'] ?? '';
        $appName = $branding['app_name'] ?? 'HOSTY';

        return static::truncate(
            $tagline ?: "Tìm và thuê phòng trọ, căn hộ tại TP.HCM — {$appName}",
            160,
        );
    }

    public static function defaultImage(?array $branding = null): ?string
    {
        $branding = $branding ?? static::branding();
        $logo = SystemSetting::normalizeLogoPath($branding['logo_path'] ?? null);

        if ($logo === null) {
            return null;
        }

        return static::absoluteUrl(
            str_starts_with($logo, 'http') ? $logo : asset('storage/'.$logo),
        );
    }

    public static function forHome(?array $branding = null): self
    {
        $branding = $branding ?? static::branding();
        $appName = $branding['app_name'] ?? 'HOSTY';
        $tagline = $branding['tagline'] ?? '';
        $title = $tagline ? "{$appName} - {$tagline}" : $appName;

        return new self(
            title: $title,
            description: static::defaultDescription($branding),
            canonical: url('/'),
            image: static::defaultImage($branding),
            type: 'website',
            siteName: $appName,
        );
    }

    public static function forListing(Listing $listing, ?array $branding = null): self
    {
        $branding = $branding ?? static::branding();
        $appName = $branding['app_name'] ?? 'HOSTY';
        $district = $listing->room?->building?->district ?? 'TP.HCM';
        $title = "{$listing->title} - {$district} - {$appName}";
        $plain = static::plainText($listing->description);
        $price = number_format($listing->price, 0, ',', '.');
        $description = static::truncate(
            $plain ?: "Cho thuê {$listing->title} tại {$district}. Giá {$price}đ/tháng.",
            160,
        );

        return new self(
            title: $title,
            description: $description,
            canonical: route('listing.show', $listing->slug),
            image: static::absoluteUrl($listing->thumbnail_url) ?? static::defaultImage($branding),
            type: 'article',
            siteName: $appName,
        );
    }

    public static function forMap(?array $branding = null): self
    {
        $branding = $branding ?? static::branding();
        $appName = $branding['app_name'] ?? 'HOSTY';

        return new self(
            title: "Bản đồ phòng trọ - {$appName}",
            description: static::truncate("Xem bản đồ và danh sách phòng trọ cho thuê trên {$appName}.", 160),
            canonical: route('map.index'),
            image: static::defaultImage($branding),
            siteName: $appName,
        );
    }

    public static function forContact(Listing $listing, ?array $branding = null): self
    {
        $branding = $branding ?? static::branding();
        $base = static::forListing($listing, $branding);

        return new self(
            title: "Liên hệ - {$listing->title}",
            description: static::truncate("Gửi yêu cầu xem phòng {$listing->title}. Chủ trọ sẽ liên hệ sớm.", 160),
            canonical: route('listing.contact', $listing->slug),
            image: $base->image,
            type: 'website',
            siteName: $base->siteName,
        );
    }

    public static function forFilters(?array $branding = null): self
    {
        $branding = $branding ?? static::branding();

        return new self(
            title: 'Bộ lọc',
            description: static::defaultDescription($branding),
            canonical: route('home'),
            image: static::defaultImage($branding),
            robots: 'noindex, follow',
            siteName: $branding['app_name'] ?? 'HOSTY',
        );
    }

    public static function noindex(string $title, ?string $canonical = null): self
    {
        return new self(
            title: $title,
            description: '',
            canonical: $canonical ?? url()->current(),
            robots: 'noindex, nofollow',
        );
    }

    private static function plainText(?string $html): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags($html ?? '')));
    }

    private static function truncate(string $text, int $length): string
    {
        return Str::limit($text, $length, '…');
    }
}
