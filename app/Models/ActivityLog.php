<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = ['type', 'title', 'description', 'subject_type', 'subject_id', 'meta'];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public static function log(string $type, string $title, ?string $description = null, ?Model $subject = null, array $meta = []): self
    {
        return static::create([
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'meta' => $meta,
        ]);
    }
}
