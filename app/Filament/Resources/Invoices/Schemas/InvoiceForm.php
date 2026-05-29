<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('contract_id')->label('Hợp đồng')->relationship('contract', 'id')->getOptionLabelFromRecordUsing(fn ($c) => "{$c->tenant?->full_name} - P.{$c->room?->room_number}")->searchable()->required(),
                    TextInput::make('billing_month')->label('Tháng (YYYY-MM)')->required(),
                    TextInput::make('room_amount')->label('Tiền phòng')->numeric()->prefix('₫'),
                    TextInput::make('electric_amount')->label('Tiền điện')->numeric()->prefix('₫'),
                    TextInput::make('water_amount')->label('Tiền nước')->numeric()->prefix('₫'),
                    TextInput::make('service_amount')->label('Phí DV')->numeric()->prefix('₫'),
                    TextInput::make('other_amount')->label('Phát sinh')->numeric()->prefix('₫'),
                    TextInput::make('total_amount')->label('Tổng cộng')->numeric()->prefix('₫')->required(),
                    DatePicker::make('due_date')->label('Hạn thanh toán')->required(),
                    Select::make('status')->label('Trạng thái')->options(collect(InvoiceStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))->required(),
                ]),
            ]),
        ]);
    }
}
