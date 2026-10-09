<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lookbooks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('2025 LOOKBOOK');
            $table->string('year', 20)->default('2025');
            $table->string('video_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('fotoshoots', function (Blueprint $table) {
            $table->foreignId('lookbook_id')->nullable()->constrained('lookbooks')->onDelete('cascade');
        });

        // Create default 2025 lookbook and link existing fotoshoots
        $defaultLb = \Illuminate\Support\Facades\DB::table('lookbooks')->insertGetId([
            'title' => '2025 LOOKBOOK',
            'year' => '2025',
            'video_url' => 'video/editorial.mp4',
            'cover_image' => 'home.jpg',
            'description' => 'Notisse 2025 Spring / Summer Visual Campaign',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('fotoshoots')->whereNull('lookbook_id')->update([
            'lookbook_id' => $defaultLb
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fotoshoots', function (Blueprint $table) {
            $table->dropForeign(['lookbook_id']);
            $table->dropColumn('lookbook_id');
        });

        Schema::dropIfExists('lookbooks');
    }
};
