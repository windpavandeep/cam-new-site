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
        Schema::create('video_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Default categories (match previous slug-style for migration)
        DB::table('video_categories')->insert([
            ['name' => 'Milling', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Multi-Axis', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turning', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_categories');
    }
};
