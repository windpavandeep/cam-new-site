<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'youtube_id',
        'title',
        'pdf',
        'category_id',
        'media_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class, 'category_id');
    }

    /** Media from the library assigned as this video's model (e.g. PDF). */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** Resolved model filename for download: from media if set, else legacy pdf. */
    public function getModelFilenameAttribute(): ?string
    {
        if ($this->media_id && $this->relationLoaded('media') && $this->media) {
            return $this->media->path;
        }
        if ($this->media_id) {
            $media = $this->media()->first();
            return $media ? $media->path : $this->pdf;
        }

        return $this->pdf;
    }

    /** Whether the model is served from the media library. */
    public function hasMediaModel(): bool
    {
        return (bool) $this->media_id;
    }

    /**
     * Download path for route: "file/{filename}" (legacy) or "media/{id}".
     */
    public function getDownloadPathAttribute(): ?string
    {
        if ($this->hasMediaModel()) {
            return 'media/' . $this->media_id;
        }
        if ($this->pdf) {
            return 'file/' . $this->pdf;
        }

        return null;
    }

    /**
     * @return array{id: string, title: string, pdf: string|null, download_path: string|null, media_id: int|null, tooltip: string|null, thumbnail_url: string|null}
     */
    public function toHomeArray(): array
    {
        $media = $this->relationLoaded('media') ? $this->media : $this->media()->first();
        $pdf = $this->hasMediaModel() && $media
            ? $media->original_name
            : $this->pdf;

        return [
            'id' => $this->youtube_id,
            'title' => $this->title,
            'pdf' => $pdf,
            'download_path' => $this->download_path,
            'media_id' => $this->media_id,
            'tooltip' => $media?->tooltip,
            'thumbnail_url' => $media?->thumbnail_url,
        ];
    }

}
