<?php

namespace App\Enums;

enum ContractStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Expired = 'expired';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Nháp',
            self::Active => 'Đang hiệu lực',
            self::Expired => 'Hết hạn',
            self::Terminated => 'Chấm dứt',
        };
    }
}
