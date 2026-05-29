<?php

namespace App\Filament\Resources\UtilityReadings\Pages;

use App\Filament\Resources\UtilityReadings\UtilityReadingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUtilityReading extends EditRecord
{
    protected static string $resource = UtilityReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
