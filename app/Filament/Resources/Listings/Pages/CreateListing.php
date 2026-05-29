<?php

namespace App\Filament\Resources\Listings\Pages;

use App\Filament\Resources\Listings\ListingResource;
use App\Models\Listing;
use App\Support\VietnameseSlug;
use Filament\Resources\Pages\CreateRecord;

class CreateListing extends CreateRecord
{
    protected static string $resource = ListingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $base = filled($data['slug'] ?? '')
            ? VietnameseSlug::from($data['slug'])
            : VietnameseSlug::from($data['title'] ?? '');

        $data['slug'] = VietnameseSlug::uniqueForListing($base);
        $data['images'] = Listing::normalizeImagePaths($data['images'] ?? []);

        return $data;
    }
}
