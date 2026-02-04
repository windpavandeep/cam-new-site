<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('pdf')->constrained('video_categories')->cascadeOnDelete();
        });

        // Map old category string to video_categories id (Milling=1, Multi-Axis=2, Turning=3 from insert order)
        $map = [
            'milling' => 1,
            'multi_axis' => 2,
            'turning' => 3,
        ];
        foreach ($map as $slug => $category_id) {
            DB::table('videos')->where('category', $slug)->update(['category_id' => $category_id]);
        }

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('category')->nullable()->after('pdf');
        });
        $map = [
            1 => 'milling',
            2 => 'multi_axis',
            3 => 'turning',
        ];
        foreach ($map as $category_id => $slug) {
            DB::table('videos')->where('category_id', $category_id)->update(['category' => $slug]);
        }
        Schema::table('videos', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
