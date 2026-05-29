<?php

namespace App\Filament\Resources\Buildings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BuildingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin tòa nhà')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->label('Tên tòa nhà')->required()->maxLength(255),
                    TextInput::make('district')->label('Quận/Huyện'),
                    TextInput::make('address')->label('Địa chỉ')->required()->columnSpanFull(),
                    TextInput::make('city')->label('Thành phố')->default('TP.HCM'),
                    TextInput::make('lat')->label('Vĩ độ')->numeric(),
                    TextInput::make('lng')->label('Kinh độ')->numeric(),
                    Toggle::make('is_active')->label('Đang hoạt động')->default(true),
                ]),
                Textarea::make('description')->label('Mô tả')->rows(3)->columnSpanFull(),
                TagsInput::make('amenities')->label('Tiện ích')->columnSpanFull(),
                FileUpload::make('images')->label('Hình ảnh')->multiple()->image()->directory('buildings')->disk('public'),
            ]),
        ]);
    }
}
