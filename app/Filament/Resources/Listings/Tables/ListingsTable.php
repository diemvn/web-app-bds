<?php

namespace App\Filament\Resources\Listings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Tiêu đề')->searchable()->limit(40),
                TextColumn::make('price')->label('Giá')->money('VND'),
                TextColumn::make('status')->label('Trạng thái')->badge(),
                TextColumn::make('published_at')->label('Đăng lúc')->dateTime('d/m/Y'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
