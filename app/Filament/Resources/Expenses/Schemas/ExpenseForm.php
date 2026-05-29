<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('building_id')->label('Tòa nhà')->relationship('building', 'name'),
                    Select::make('category')->label('Loại')->options([
                        'electricity' => 'Điện chung',
                        'water' => 'Nước chung',
                        'labor' => 'Nhân công',
                        'maintenance' => 'Sửa chữa',
                        'other' => 'Khác',
                    ])->required(),
                    TextInput::make('title')->label('Tiêu đề')->required(),
                    TextInput::make('amount')->label('Số tiền')->numeric()->required()->prefix('₫'),
                    DatePicker::make('expense_date')->label('Ngày chi')->required(),
                ]),
                FileUpload::make('receipt_image')->label('Chứng từ')->image()->directory('expenses')->disk('public'),
                Textarea::make('notes')->label('Ghi chú')->columnSpanFull(),
            ]),
        ]);
    }
}
