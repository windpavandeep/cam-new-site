<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A candidate application with CV stored on the local disk.
 *
 * @property int $id
 * @property int $job_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $cover_letter
 * @property string $cv_path
 * @property string|null $cv_original_name
 * @property \Illuminate\Support\Carbon|null $read_at
 */
class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'cv_path',
        'cv_original_name',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function markRead(): void
    {
        if ($this->read_at === null) {
            $this->read_at = now();
            $this->save();
        }
    }

    public function delete_cv_file(): void
    {
        if ($this->cv_path !== '' && Storage::disk('local')->exists($this->cv_path)) {
            Storage::disk('local')->delete($this->cv_path);
        }
    }

    protected static function booted(): void
    {
        static::deleting(function (JobApplication $application): void {
            $application->delete_cv_file();
        });
    }

    /**
     * @return BelongsTo<Job, $this>
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
