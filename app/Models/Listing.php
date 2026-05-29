<?php

namespace App\Models;

use App\Enums\ListingStatus;
use App\Support\VietnameseSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class Listing extends Model
{
    protected $fillable = [
        'room_id', 'title', 'slug', 'description', 'price', 'area_m2',
        'lat', 'lng', 'images', 'amenities', 'status', 'published_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ListingStatus::class,
            'images' => 'array',
            'amenities' => 'array',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'area_m2' => 'decimal:2',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Listing $listing) {
            if (empty($listing->slug) && filled($listing->title)) {
                $listing->slug = VietnameseSlug::uniqueForListing($listing->title, $listing->id);
            }
        });
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ListingStatus::Published);
    }

    public function scopeWithinBounds(Builder $query, ?float $swLat, ?float $swLng, ?float $neLat, ?float $neLng): Builder
    {
        if ($swLat === null || $swLng === null || $neLat === null || $neLng === null) {
            return $query;
        }

        return $query
            ->whereBetween('lat', [min($swLat, $neLat), max($swLat, $neLat)])
            ->whereBetween('lng', [min($swLng, $neLng), max($swLng, $neLng)]);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $images = static::normalizeImagePaths($this->images);

        if (empty($images)) {
            return null;
        }

        $first = $images[0];

        return str_starts_with($first, 'http') ? $first : asset('storage/'.$first);
    }

    /**
     * @return list<string>
     */
    public static function normalizeImagePaths(mixed $images): array
    {
        return collect(Arr::wrap($images))
            ->flatMap(function (mixed $item): array {
                if (is_string($item) && filled($item)) {
                    return [$item];
                }

                if (! is_array($item)) {
                    return [];
                }

                if (isset($item['path']) && is_string($item['path']) && filled($item['path'])) {
                    return [$item['path']];
                }

                return collect($item)
                    ->filter(fn (mixed $value): bool => is_string($value) && filled($value))
                    ->values()
                    ->all();
            })
            ->unique()
            ->values()
            ->all();
    }
}
