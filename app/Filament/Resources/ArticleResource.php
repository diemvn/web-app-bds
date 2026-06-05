<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    
    public static function getNavigationGroup(): ?string
    {
        return 'Nội dung';
    }

    protected static ?string $modelLabel = 'Bài viết';
    protected static ?string $pluralModelLabel = 'Bài viết';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Nội dung chính')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Tiêu đề')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('slug')
                                    ->maxLength(255),
                                Forms\Components\Select::make('article_category_id')
                                    ->label('Danh mục')
                                    ->relationship('category', 'name')
                                    ->searchable(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Tóm tắt')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('content')
                                    ->label('Nội dung')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Ảnh đại diện')
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->label('Thumbnail')
                                    ->image()
                                    ->directory('articles'),
                            ]),
                        Section::make('Xuất bản')
                            ->schema([
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Xuất bản')
                                    ->default(false),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Ngày xuất bản'),
                                Forms\Components\TextInput::make('reading_time')
                                    ->label('Thời gian đọc (phút)')
                                    ->numeric(),
                                Forms\Components\Hidden::make('author_id')
                                    ->default(fn () => auth()->id()),
                            ]),
                        Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('seo_description')
                                    ->maxLength(65535),
                            ])
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Xuất bản')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Lượt xem')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Trạng thái xuất bản'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
