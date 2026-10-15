<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_certificates', function (Blueprint $table) {
            $table->string('student_photo_path')->nullable()->after('path');
            $table->string('student_photo_original_name')->nullable()->after('student_photo_path');
            $table->string('student_photo_mime_type')->nullable()->after('student_photo_original_name');
        });
    }

    public function down(): void
    {
        Schema::table('student_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'student_photo_path',
                'student_photo_original_name',
                'student_photo_mime_type',
            ]);
        });
    }
};
