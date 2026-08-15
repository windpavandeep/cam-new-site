<?php

use App\Models\StudentCertificate;
use App\Support\PublicAsset;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    private const string OLD_DIR = 'certificates';

    private const string NEW_DIR = 'student-certificates';

    public function up(): void
    {
        $new_disk = PublicAsset::diskPath(self::NEW_DIR);
        if (! is_dir($new_disk)) {
            File::ensureDirectoryExists($new_disk);
        }

        StudentCertificate::query()
            ->where('path', 'like', self::OLD_DIR.'/%')
            ->orderBy('id')
            ->each(function (StudentCertificate $certificate): void {
                $filename = basename((string) $certificate->path);
                $old_disk = PublicAsset::diskPath(self::OLD_DIR.'/'.$filename);
                $new_disk_file = PublicAsset::diskPath(self::NEW_DIR.'/'.$filename);

                if (is_file($old_disk)) {
                    if (! is_file($new_disk_file)) {
                        rename($old_disk, $new_disk_file);
                    } else {
                        @unlink($old_disk);
                    }
                }

                $certificate->update([
                    'path' => self::NEW_DIR.'/'.$filename,
                ]);
            });

        $old_disk_dir = PublicAsset::diskPath(self::OLD_DIR);
        if (is_dir($old_disk_dir) && count(scandir($old_disk_dir)) === 2) {
            @rmdir($old_disk_dir);
        }
    }

    public function down(): void
    {
        $old_disk = PublicAsset::diskPath(self::OLD_DIR);
        if (! is_dir($old_disk)) {
            File::ensureDirectoryExists($old_disk);
        }

        StudentCertificate::query()
            ->where('path', 'like', self::NEW_DIR.'/%')
            ->orderBy('id')
            ->each(function (StudentCertificate $certificate): void {
                $filename = basename((string) $certificate->path);
                $new_disk = PublicAsset::diskPath(self::NEW_DIR.'/'.$filename);
                $old_disk_file = PublicAsset::diskPath(self::OLD_DIR.'/'.$filename);

                if (is_file($new_disk)) {
                    if (! is_file($old_disk_file)) {
                        rename($new_disk, $old_disk_file);
                    }
                }

                $certificate->update([
                    'path' => self::OLD_DIR.'/'.$filename,
                ]);
            });
    }
};
