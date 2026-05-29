<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('full_name')->label('Họ tên')->required(),
                    TextInput::make('phone')->label('SĐT')->tel()->required()->unique(ignoreRecord: true),
                    TextInput::make('email')->label('Email')->email(),
                    TextInput::make('cccd')->label('CCCD'),
                    TextInput::make('zalo_user_id')->label('Zalo User ID'),
                ]),
                FileUpload::make('documents')->label('Ảnh CCCD')->multiple()->image()->directory('tenants')->disk('public')->columnSpanFull(),
                Textarea::make('notes')->label('Ghi chú')->columnSpanFull(),
            ]),
        ]);
    }
}
