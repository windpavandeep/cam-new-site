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

    /** Public URL for the thumbnail, or the main file when it is an image. */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            $thumb_disk = PublicAsset::diskPath((string) $this->thumbnail_path);
            if (is_file($thumb_disk)) {
                return PublicAsset::url((string) $this->thumbnail_path);
            }
        }

        if ($this->isImageFile() && is_file(PublicAsset::diskPath((string) $this->path))) {
            return PublicAsset::url((string) $this->path);
        }

        return null;
    }

    /** Whether this media has a dedicated thumbnail or is itself an image. */
    public function hasThumbnail(): bool
    {
        return $this->thumbnail_url !== null;
    }

    public function isImageFile(): bool
    {
        if (str_starts_with((string) $this->mime_type, 'image/')) {
            return true;
        }

        return (bool) preg_match('/\.(jpe?g|png|gif|webp|bmp|svg)$/i', (string) $this->original_name);
    }

    /** Videos that use this media as their model (PDF). */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
