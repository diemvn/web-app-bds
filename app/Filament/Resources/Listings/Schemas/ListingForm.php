<?php

namespace App\Filament\Resources\Listings\Schemas;

use App\Enums\ListingStatus;
use App\Support\VietnameseSlug;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Arr;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ListingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('room_id')
                        ->label('Phòng')
                        ->relationship('room', 'room_number')
                        ->searchable()
                        ->required(),
                    TextInput::make('title')
                        ->label('Tiêu đề')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state, $livewire): void {
                            if ($livewire instanceof EditRecord && filled($get('slug'))) {
                                return;
                            }
                            $set('slug', VietnameseSlug::from($state ?? ''));
                        })
                        ->columnSpanFull(),
                    TextInput::make('slug')
                        ->label('URL (slug)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Tự động từ tiêu đề, không dấu. VD: phong-20m2-full-noi-that')
                        ->columnSpanFull(),
                    TextInput::make('price')->label('Giá đăng')->numeric()->required()->prefix('₫'),
                    TextInput::make('area_m2')->label('Diện tích')->numeric(),
                    TextInput::make('lat')->label('Vĩ độ')->numeric(),
                    TextInput::make('lng')->label('Kinh độ')->numeric(),
                    Select::make('status')
                        ->label('Trạng thái')
                        ->options(collect(ListingStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                        ->required(),
                ]),
                RichEditor::make('description')->label('Mô tả')->columnSpanFull(),
                TagsInput::make('amenities')
                    ->label('Tiện ích')
                    ->suggestions(config('hosty.amenity_presets', []))
                    ->splitKeys(['Tab', ','])
                    ->placeholder('Gõ hoặc chọn gợi ý...')
                    ->helperText('Chọn nhanh từ gợi ý hoặc thêm tag mới')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->label('Hình ảnh')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->directory('listings')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(function (BaseFileUpload $component, mixed $file, string | array | null $storedFileNames): ?array {
                        if (is_array($file)) {
                            $file = $file['path'] ?? Arr::first(Arr::flatten($file));
                        }

                        if (! is_string($file) || blank($file)) {
                            return null;
                        }

                        return $component->getUploadedFile($file, $storedFileNames);
                    })
                    ->reorderable()
                    ->maxFiles(10)
                    ->panelLayout('grid')
                    ->imagePreviewHeight('150')
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
