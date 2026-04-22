<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * A job posting shown on the public careers page (managed in admin).
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string|null $location
 * @property string|null $employment_type
 * @property bool $is_published
 * @property int $sort_order
 */
class Job extends Model
{
    protected $table = 'job_postings';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'employment_type',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (Job $job): void {
            if ($job->title === '') {
                return;
            }
            if ($job->isDirty('title') || $job->slug === null || $job->slug === '') {
                $job->slug = self::unique_slug_from_title($job->title, $job->id);
            }
        });

        static::deleting(function (Job $job): void {
            // DB may cascade-delete application rows without model events; remove CV files first.
            $job->applications->each(static function (JobApplication $application): void {
                $application->delete_cv_file();
            });
        });
    }

    public static function unique_slug_from_title(string $title, ?int $ignore_id): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'position';
        }
        $slug = $base;
        $n = 2;
        while (self::query()
            ->where('slug', $slug)
            ->when($ignore_id !== null, fn ($q) => $q->where('id', '!=', $ignore_id))
            ->exists()) {
            $slug = $base . '-' . $n;
            $n++;
        }

        return $slug;
    }

    /**
     * @return HasMany<JobApplication, $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }
}
