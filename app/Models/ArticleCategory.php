<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Support\VietnameseSlug;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = VietnameseSlug::make($category->name);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
