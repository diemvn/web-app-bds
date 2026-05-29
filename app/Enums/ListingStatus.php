<?php

namespace App\Enums;

enum ListingStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Paused = 'paused';
    case Rented = 'rented';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Nháp',
            self::Published => 'Đang đăng',
            self::Paused => 'Tạm dừng',
            self::Rented => 'Đã cho thuê',
        };
    }
}
