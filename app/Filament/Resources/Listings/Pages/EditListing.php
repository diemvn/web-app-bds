<?php

namespace App\Filament\Resources\Listings\Pages;

use App\Filament\Resources\Listings\ListingResource;
use App\Models\Listing;
use App\Support\VietnameseSlug;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditListing extends EditRecord
{
    protected static string $resource = ListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['images'] = Listing::normalizeImagePaths($data['images'] ?? []);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['images'] = Listing::normalizeImagePaths($data['images'] ?? []);

        $base = filled($data['slug'] ?? '')
            ? VietnameseSlug::from($data['slug'])
            : VietnameseSlug::from($data['title'] ?? '');

        $data['slug'] = VietnameseSlug::uniqueForListing($base, $this->record->id);

        return $data;
    }
}
