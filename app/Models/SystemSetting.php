<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (! Schema::hasTable('system_settings')) {
            return $default;
        }

        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::query()->where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        if (! Schema::hasTable('system_settings')) {
            return;
        }

        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
        Cache::forget('branding');
    }

    /**
     * Filament FileUpload may persist logo_path as a string or single-element array.
     */
    public static function normalizeLogoPath(mixed $path): ?string
    {
        if (is_string($path) && filled($path)) {
            return $path;
        }

        if (! is_array($path)) {
            return null;
        }

        foreach (Arr::flatten($path) as $item) {
            if (is_string($item) && filled($item)) {
                return $item;
            }
        }

        return null;
    }

    public static function branding(): array
    {
        if (! Schema::hasTable('system_settings')) {
            return [
                'app_name' => 'Quản lý phòng trọ',
                'primary_color' => '#FF5A5F',
                'logo_path' => null,
                'tagline' => '',
                'meta_description_default' => '',
                'google_site_verification' => '',
            ];
        }

        return Cache::remember('branding', 3600, fn () => [
            'app_name' => static::get('app_name', 'Quản lý phòng trọ'),
            'primary_color' => static::get('primary_color', '#FF5A5F'),
            'logo_path' => static::normalizeLogoPath(static::get('logo_path')),
            'tagline' => static::get('tagline', 'Hệ thống quản lý phòng trọ'),
            'meta_description_default' => static::get('meta_description_default', ''),
            'google_site_verification' => static::get('google_site_verification', ''),
        ]);
    }
}
