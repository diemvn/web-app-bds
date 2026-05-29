<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.contract.tenant.full_name')->label('Khách'),
                TextColumn::make('amount')->label('Số tiền')->money('VND'),
                TextColumn::make('method')->label('PT')->badge(),
                TextColumn::make('paid_at')->label('Ngày TT')->dateTime('d/m/Y H:i'),
                TextColumn::make('transaction_ref')->label('Mã GD'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
