@props(['class' => ''])

<a href="{{ $href ?? '/' }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 font-bold text-xl '.$class]) }} style="color: var(--color-primary, #FF5A5F)">
    @php($logoPath = \App\Models\SystemSetting::normalizeLogoPath($branding['logo_path'] ?? null))
    @if($logoPath)
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath) }}" alt="{{ $branding['app_name'] ?? 'HOSTY' }}" class="h-8 w-auto">
    @else
        <span class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-sm font-bold" style="background: var(--color-primary)">H</span>
    @endif
    <span>{{ $branding['app_name'] ?? 'HOSTY' }}</span>
</a>
