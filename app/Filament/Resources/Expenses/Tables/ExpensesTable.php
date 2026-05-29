<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Tiêu đề')->searchable(),
                TextColumn::make('category')->label('Loại'),
                TextColumn::make('amount')->label('Số tiền')->money('VND'),
                TextColumn::make('expense_date')->label('Ngày')->date('d/m/Y'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
