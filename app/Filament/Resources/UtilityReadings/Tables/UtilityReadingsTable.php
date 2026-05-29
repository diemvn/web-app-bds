<?php

namespace App\Filament\Resources\UtilityReadings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UtilityReadingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.building.name')->label('Tòa nhà'),
                TextColumn::make('room.room_number')->label('Phòng'),
                TextColumn::make('reading_month')->label('Tháng'),
                TextColumn::make('electric_end')->label('Điện cuối'),
                TextColumn::make('water_end')->label('Nước cuối'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
