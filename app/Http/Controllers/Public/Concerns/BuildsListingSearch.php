<?php

namespace App\Http\Controllers\Public\Concerns;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait BuildsListingSearch
{
    protected function listingSearchQuery(Request $request): Builder
    {
        $query = Listing::query()->published()->with('room.building');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function (Builder $builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhereHas('room.building', fn (Builder $b) => $b->where('district', 'like', "%{$q}%")
                        ->orWhere('address', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int) $request->price_min * 1_000_000);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) $request->price_max * 1_000_000);
        }

        if ($request->filled('area_min')) {
            $query->where('area_m2', '>=', (float) $request->area_min);
        }

        return $query;
    }
}
