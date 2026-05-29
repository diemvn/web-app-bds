<?php

namespace App\Filament\Resources\Rooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('building.name')->label('Tòa nhà')->sortable(),
                TextColumn::make('room_number')->label('Phòng')->searchable(),
                TextColumn::make('floor')->label('Tầng'),
                TextColumn::make('base_price')->label('Giá thuê')->money('VND'),
                TextColumn::make('status')->label('Trạng thái')->badge(),
                TextColumn::make('area_m2')->label('m²'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
