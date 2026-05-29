<?php

namespace App\Filament\Resources\UtilityReadings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UtilityReadingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Chỉ số điện nước')->schema([
                Grid::make(2)->schema([
                    Select::make('room_id')
                        ->label('Phòng')
                        ->relationship(
                            'room',
                            'room_number',
                            fn ($query) => $query->with('building'),
                        )
                        ->getOptionLabelFromRecordUsing(fn ($r) => $r?->display_name ?? 'Phòng không xác định')
                        ->searchable()
                        ->required(),
                    TextInput::make('reading_month')->label('Tháng (YYYY-MM)')->required(),
                    TextInput::make('electric_start')->label('Điện đầu')->numeric()->default(0),
                    TextInput::make('electric_end')->label('Điện cuối')->numeric()->default(0),
                    TextInput::make('water_start')->label('Nước đầu')->numeric()->default(0),
                    TextInput::make('water_end')->label('Nước cuối')->numeric()->default(0),
                    TextInput::make('electric_rate')->label('Giá điện/kWh')->numeric()->default(3500),
                    TextInput::make('water_rate')->label('Giá nước/m³')->numeric()->default(20000),
                ]),
            ]),
        ]);
    }
}
