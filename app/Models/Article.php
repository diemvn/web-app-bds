<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\VietnameseSlug;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_category_id',
        'author_id',
        'title',
        'slug',
        'thumbnail',
        'excerpt',
        'content',
        'is_published',
        'published_at',
        'views_count',
        'seo_title',
        'seo_description',
        'reading_time',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($article) {
            if (empty($article->slug)) {
                $article->slug = VietnameseSlug::make($article->title);
            }
            if ($article->is_published && empty($article->published_at)) {
                $article->published_at = now();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
