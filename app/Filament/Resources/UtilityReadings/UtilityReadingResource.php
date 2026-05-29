<?php

namespace App\Filament\Resources\UtilityReadings;

use App\Filament\Resources\UtilityReadings\Pages\CreateUtilityReading;
use App\Filament\Resources\UtilityReadings\Pages\EditUtilityReading;
use App\Filament\Resources\UtilityReadings\Pages\ListUtilityReadings;
use App\Filament\Resources\UtilityReadings\Schemas\UtilityReadingForm;
use App\Filament\Resources\UtilityReadings\Tables\UtilityReadingsTable;
use App\Models\UtilityReading;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UtilityReadingResource extends Resource
{
    protected static ?string $model = UtilityReading::class;

    protected static ?string $navigationLabel = 'Chỉ số ĐN';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý';
    }

    public static function form(Schema $schema): Schema
    {
        return UtilityReadingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UtilityReadingsTable::configure($table);
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
            'index' => ListUtilityReadings::route('/'),
            'create' => CreateUtilityReading::route('/create'),
            'edit' => EditUtilityReading::route('/{record}/edit'),
        ];
    }
}
