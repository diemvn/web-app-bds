<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityReading extends Model
{
    protected $fillable = [
        'room_id', 'reading_month', 'electric_start', 'electric_end',
        'water_start', 'water_end', 'electric_rate', 'water_rate',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function getElectricUsageAttribute(): int
    {
        return max(0, $this->electric_end - $this->electric_start);
    }

    public function getWaterUsageAttribute(): int
    {
        return max(0, $this->water_end - $this->water_start);
    }

    public function getElectricCostAttribute(): int
    {
        return $this->electric_usage * $this->electric_rate;
    }

    public function getWaterCostAttribute(): int
    {
        return $this->water_usage * $this->water_rate;
    }
}
