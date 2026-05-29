<?php

namespace App\Enums;

enum RoomStatus: string
{
    case Available = 'available';
    case Occupied = 'occupied';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Trống',
            self::Occupied => 'Đang thuê',
            self::Maintenance => 'Bảo trì',
        };
    }
}
