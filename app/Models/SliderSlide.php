<?php

declare(strict_types=1);

namespace App\Models;

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

    /** URL for the slide image (stored in public/slider). */
    public function getImageUrlAttribute(): string
    {
        return (string) asset($this->image);
    }
}
