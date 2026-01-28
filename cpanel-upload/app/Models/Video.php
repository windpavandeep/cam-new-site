<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public const string CATEGORY_MILLING = 'milling';

    public const string CATEGORY_MULTI_AXIS = 'multi_axis';

    public const string CATEGORY_TURNING = 'turning';

    protected $fillable = [
        'youtube_id',
        'title',
        'pdf',
        'category',
    ];

    /**
     * @return array{id: string, title: string, pdf: string|null}
     */
    public function toHomeArray(): array
    {
        return [
            'id' => $this->youtube_id,
            'title' => $this->title,
            'pdf' => $this->pdf,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_MILLING => 'Milling',
            self::CATEGORY_MULTI_AXIS => 'Multi-Axis',
            self::CATEGORY_TURNING => 'Turning',
        ];
    }
}
