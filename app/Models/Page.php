<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'template',
        'status',
        'published_at',
        'seo_meta',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo_meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Page $page) {
            if (blank($page->slug) && filled($page->title)) {
                $page->slug = Str::slug($page->title);
            }

            if ($page->status === 'published' && blank($page->published_at)) {
                $page->published_at = now();
            }

            if ($page->status !== 'published') {
                $page->published_at = null;
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
