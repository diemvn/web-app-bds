<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';
    
    public static function getNavigationGroup(): ?string
    {
        return 'Hỗ trợ';
    }

    protected static ?string $modelLabel = 'Câu hỏi thường gặp';
    protected static ?string $pluralModelLabel = 'Câu hỏi thường gặp';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('question')
                    ->label('Câu hỏi')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('answer')
                    ->label('Trả lời')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->label('Danh mục')
                    ->default('Chung')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Hiển thị')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Câu hỏi')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category')
                    ->label('Danh mục')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Hiển thị')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageFaqs::route('/'),
        ];
    }
}
