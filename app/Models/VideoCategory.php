<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoCategory extends Model
{
    protected $table = 'video_categories';

    protected $fillable = [
        'name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'category_id');
    }

    /** Slug-safe id for tab/panel DOM ids. */
    public function getSlugAttribute(): string
    {
        return 'cat-' . $this->id;
    }
}
