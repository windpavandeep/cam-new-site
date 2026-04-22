<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\PublicAsset;
use Illuminate\Database\Eloquent\Model;

class SliderSlide extends Model
{
    protected $fillable = [
        'image',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /** URL for the slide image (stored under public/slider on disk). */
    public function getImageUrlAttribute(): string
    {
        return PublicAsset::url((string) $this->image);
    }
}
