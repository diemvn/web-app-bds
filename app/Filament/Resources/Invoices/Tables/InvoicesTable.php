<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract.tenant.full_name')->label('Khách thuê'),
                TextColumn::make('contract.room.room_number')->label('Phòng'),
                TextColumn::make('billing_month')->label('Tháng'),
                TextColumn::make('total_amount')->label('Tổng')->money('VND'),
                TextColumn::make('due_date')->label('Hạn TT')->date('d/m/Y'),
                TextColumn::make('status')->label('Trạng thái')->badge(),
            ])
            ->headerActions([
                Action::make('generate')->label('Tạo HĐ tháng này')
                    ->action(fn () => Artisan::call('hosty:generate-invoices'))
                    ->requiresConfirmation(),
            ])
            ->recordActions([EditAction::make()]);
    }
}
