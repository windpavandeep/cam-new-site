<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\PublicAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Global media library item. Files can have a thumbnail and tooltip.
 * Media IDs can be assigned to videos (YouTube) as the "model" (e.g. PDF) for that video.
 */
class Media extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'thumbnail_path',
        'tooltip',
        'mime_type',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    /** Public URL for the main file. */
    public function getUrlAttribute(): string
    {
        return PublicAsset::url((string) $this->path);
    }

    /** Public URL for the thumbnail, or null if not set. */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail_path) {
            return null;
        }

        return PublicAsset::url((string) $this->thumbnail_path);
    }

    /** Whether this media has a thumbnail. */
    public function hasThumbnail(): bool
    {
        return ! empty($this->thumbnail_path);
    }

    /** Videos that use this media as their model (PDF). */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
