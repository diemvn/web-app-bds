<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    protected $fillable = [
        'name', 'address', 'district', 'city', 'lat', 'lng',
        'amenities', 'images', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'images' => 'array',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'is_active' => 'boolean',
        ];
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function getOccupancyRateAttribute(): float
    {
        $total = $this->rooms()->count();
        if ($total === 0) {
            return 0;
        }

        $occupied = $this->rooms()->where('status', 'occupied')->count();

        return round(($occupied / $total) * 100, 1);
    }
}
