<?php

namespace App\Filament\Resources\Contracts\Schemas;

use App\Enums\ContractStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('room_id')
                        ->label('Phòng')
                        ->relationship(
                            'room',
                            'room_number',
                            fn ($query) => $query->with('building'),
                        )
                        ->getOptionLabelFromRecordUsing(fn ($r) => $r?->display_name ?? 'Phòng không xác định')
                        ->searchable()
                        ->required(),
                    Select::make('tenant_id')->label('Khách thuê')->relationship('tenant', 'full_name')->searchable()->required(),
                    DatePicker::make('start_date')->label('Ngày bắt đầu')->required(),
                    DatePicker::make('end_date')->label('Ngày kết thúc')->required(),
                    TextInput::make('monthly_rent')->label('Tiền phòng/tháng')->numeric()->required()->prefix('₫'),
                    TextInput::make('deposit_amount')->label('Tiền cọc')->numeric()->prefix('₫'),
                    TextInput::make('payment_due_day')->label('Ngày đóng tiền')->numeric()->minValue(1)->maxValue(28)->default(5),
                    Select::make('status')->label('Trạng thái')->options(collect(ContractStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))->required(),
                ]),
                Textarea::make('terms')->label('Điều khoản')->columnSpanFull(),
            ]),
        ]);
    }
}
