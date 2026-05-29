<?php

namespace App\Filament\Resources\Tenants\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Họ tên')->searchable(),
                TextColumn::make('phone')->label('SĐT')->searchable(),
                TextColumn::make('cccd')->label('CCCD'),
                TextColumn::make('activeContract.room.room_number')->label('Phòng hiện tại'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
