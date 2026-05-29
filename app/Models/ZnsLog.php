<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZnsLog extends Model
{
    protected $fillable = ['template', 'phone', 'payload', 'status', 'response'];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }
}
