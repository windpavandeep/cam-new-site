<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Per-download-path totals (e.g. media/12, file/model.pdf) for authenticated model downloads.
 */
class DownloadCounter extends Model
{
    protected $table = 'download_counters';

    protected $fillable = [
        'path_key',
        'count',
    ];

    protected function casts(): array
    {
        return [
            'count' => 'integer',
        ];
    }

    /**
     * @param  array<int, string>  $path_keys
     * @return array<string, int>
     */
    public static function countsForPathKeys(array $path_keys): array
    {
        if ($path_keys === []) {
            return [];
        }

        return self::query()
            ->whereIn('path_key', $path_keys)
            ->pluck('count', 'path_key')
            ->map(fn (int|string $c): int => (int) $c)
            ->all();
    }

    public static function incrementForPath(string $path_key): int
    {
        $row = self::query()->firstOrCreate(
            ['path_key' => $path_key],
            ['count' => 0]
        );
        $row->increment('count');

        return (int) $row->fresh()->count;
    }
}
