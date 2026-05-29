<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'building_id', 'category', 'title', 'amount', 'expense_date', 'receipt_image', 'notes',
    ];

    protected function casts(): array
    {
        return ['expense_date' => 'date'];
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
}
