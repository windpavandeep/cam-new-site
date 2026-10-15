<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\PublicAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Certificate uploaded by admin; past students retrieve it using ref_no.
 */
class StudentCertificate extends Model
{
    /** Public disk folder for uploaded files (must not match URL /certificates). */
    public const string STORAGE_DIR = 'student-certificates';

    /** Subfolder for student photos. */
    public const string PHOTO_DIR = 'student-certificates/photos';

    protected $fillable = [
        'ref_no',
        'student_name',
        'father_name',
        'course_name',
        'start_date',
        'end_date',
        'path',
        'student_photo_path',
        'student_photo_original_name',
        'student_photo_mime_type',
        'original_name',
        'mime_type',
        'size',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'issued_at' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function getUrlAttribute(): string
    {
        return PublicAsset::url((string) $this->path);
    }

    public function getStudentPhotoUrlAttribute(): ?string
    {
        if (! $this->student_photo_path) {
            return null;
        }

        if (! is_file(PublicAsset::diskPath((string) $this->student_photo_path))) {
            return null;
        }

        return PublicAsset::url((string) $this->student_photo_path);
    }

    public function hasStudentPhoto(): bool
    {
        return $this->student_photo_url !== null;
    }

    public static function normalizeRefNo(string $ref_no): string
    {
        return strtoupper(trim($ref_no));
    }

    /**
     * Generate a unique reference number for new uploads (e.g. CAM2026A3K9F2).
     */
    public static function generateRefNo(): string
    {
        $prefix = 'CAM'.date('Y');

        do {
            $ref_no = $prefix.strtoupper(Str::random(6));
        } while (self::query()->where('ref_no', $ref_no)->exists());

        return $ref_no;
    }

    public function isPdf(): bool
    {
        return str_contains((string) $this->mime_type, 'pdf')
            || str_ends_with(strtolower((string) $this->original_name), '.pdf');
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }
}
