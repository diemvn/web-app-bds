<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $fillable = [
        'building_id', 'floor', 'room_number', 'area_m2', 'base_price',
        'status', 'utilities_config', 'amenities', 'images', 'description',
    ];

    protected function casts(): array
    {
        return [
            'status' => RoomStatus::class,
            'utilities_config' => 'array',
            'amenities' => 'array',
            'images' => 'array',
            'area_m2' => 'decimal:2',
        ];
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(Contract::class)->where('status', 'active')->latestOfMany();
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function utilityReadings(): HasMany
    {
        return $this->hasMany(UtilityReading::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $building = $this->building?->name ?? 'Tòa nhà';

        return "{$building} - P.{$this->room_number}";
    }
}
