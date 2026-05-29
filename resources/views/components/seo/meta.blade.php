@props(['seo', 'branding' => []])

@php
    use App\Support\SeoData;

    $seo = $seo instanceof SeoData ? $seo : SeoData::forHome($branding);
    $siteName = $seo->siteName ?? ($branding['app_name'] ?? 'HOSTY');
    $image = SeoData::absoluteUrl($seo->image) ?? SeoData::defaultImage($branding);
    $verification = trim($branding['google_site_verification'] ?? '');
@endphp

<meta name="description" content="{{ $seo->description }}">
<meta name="robots" content="{{ $seo->robots }}">
<link rel="canonical" href="{{ $seo->canonical }}">

<meta property="og:title" content="{{ $seo->title }}">
<meta property="og:description" content="{{ $seo->description }}">
<meta property="og:url" content="{{ $seo->canonical }}">
<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:locale" content="vi_VN">
<meta property="og:site_name" content="{{ $siteName }}">
@if($image)
<meta property="og:image" content="{{ $image }}">
@endif

<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seo->title }}">
<meta name="twitter:description" content="{{ $seo->description }}">
@if($image)
<meta name="twitter:image" content="{{ $image }}">
@endif

@if($verification !== '')
<meta name="google-site-verification" content="{{ $verification }}">
@endif

@stack('json-ld')
