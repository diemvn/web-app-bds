<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    
    public static function getNavigationGroup(): ?string
    {
        return 'Hỗ trợ';
    }

    protected static ?string $modelLabel = 'Yêu cầu hỗ trợ';
    protected static ?string $pluralModelLabel = 'Yêu cầu hỗ trợ';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->label('Tài khoản'),
                Forms\Components\TextInput::make('name')
                    ->label('Tên người gửi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subject')
                    ->label('Chủ đề')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'resolved' => 'Đã giải quyết',
                        'closed' => 'Đã đóng',
                    ])
                    ->required()
                    ->default('new'),
                Forms\Components\Textarea::make('message')
                    ->label('Nội dung')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('SĐT')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Chủ đề')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'resolved' => 'Đã giải quyết',
                        'closed' => 'Đã đóng',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'resolved' => 'Đã giải quyết',
                        'closed' => 'Đã đóng',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSupportTickets::route('/'),
        ];
    }
}
