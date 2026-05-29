<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentMethod;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('invoice_id')->label('Hóa đơn')->relationship('invoice', 'id')->getOptionLabelFromRecordUsing(fn ($i) => "#{$i->id} - {$i->billing_month} - ".number_format($i->total_amount).'đ')->searchable()->required(),
                    TextInput::make('amount')->label('Số tiền')->numeric()->required()->prefix('₫'),
                    Select::make('method')->label('Phương thức')->options(collect(PaymentMethod::cases())->mapWithKeys(fn ($m) => [$m->value => $m->label()]))->required(),
                    TextInput::make('transaction_ref')->label('Mã GD'),
                    DateTimePicker::make('paid_at')->label('Thời gian TT')->required(),
                ]),
                Textarea::make('notes')->label('Ghi chú')->columnSpanFull(),
            ]),
        ]);
    }
}
