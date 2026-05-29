<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;

class BrandingService
{
    public function all(): array
    {
        return SystemSetting::branding();
    }

    public function appName(): string
    {
        return $this->all()['app_name'] ?? 'Quản lý phòng trọ';
    }

    public function primaryColor(): string
    {
        return $this->all()['primary_color'] ?? '#FF5A5F';
    }

    public function logoUrl(): ?string
    {
        $path = SystemSetting::normalizeLogoPath($this->all()['logo_path'] ?? null);

        if ($path === null) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public function tagline(): string
    {
        return $this->all()['tagline'] ?? '';
    }
}
