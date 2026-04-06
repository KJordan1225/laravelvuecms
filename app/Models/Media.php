<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'disk',
        'path',
        'filename',
        'mime_type',
        'size',
        'alt_text',
        'title',
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute(): ?string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}