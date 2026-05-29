<?php

namespace App\Filament\Resources\Rooms\Schemas;

use App\Enums\RoomStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('building_id')->label('Tòa nhà')->relationship('building', 'name')->required()->searchable(),
                    TextInput::make('room_number')->label('Số phòng')->required(),
                    TextInput::make('floor')->label('Tầng')->numeric()->default(1),
                    TextInput::make('area_m2')->label('Diện tích (m²)')->numeric(),
                    TextInput::make('base_price')->label('Giá thuê (VNĐ)')->numeric()->required()->prefix('₫'),
                    Select::make('status')->label('Trạng thái')->options(collect(RoomStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))->required(),
                ]),
                Textarea::make('description')->label('Mô tả')->columnSpanFull(),
                TagsInput::make('amenities')->label('Tiện ích')->columnSpanFull(),
                FileUpload::make('images')->label('Hình ảnh')->multiple()->image()->directory('rooms')->disk('public')->columnSpanFull(),
            ]),
        ]);
    }
}
