<?php

namespace App\Filament\Resources\Contracts\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.full_name')->label('Khách thuê')->searchable(),
                TextColumn::make('room.room_number')->label('Phòng'),
                TextColumn::make('start_date')->label('Bắt đầu')->date('d/m/Y'),
                TextColumn::make('end_date')->label('Kết thúc')->date('d/m/Y'),
                TextColumn::make('monthly_rent')->label('Giá thuê')->money('VND'),
                TextColumn::make('status')->label('Trạng thái')->badge(),
            ])
            ->recordActions([EditAction::make()]);
    }
}
