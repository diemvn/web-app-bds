<?php

namespace App\Models;

use App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'room_id', 'tenant_id', 'start_date', 'end_date', 'payment_due_day',
        'deposit_amount', 'monthly_rent', 'status', 'contract_pdf_path', 'terms',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContractStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->status === ContractStatus::Active
            && $this->end_date->lte(now()->addDays($days));
    }
}
