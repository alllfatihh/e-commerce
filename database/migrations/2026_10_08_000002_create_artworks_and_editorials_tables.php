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
        if (!Schema::hasTable('artworks')) {
            Schema::create('artworks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');
                $table->string('title');
                $table->string('medium')->nullable();
                $table->string('dimensions')->nullable();
                $table->string('year')->nullable();
                $table->string('status')->default('Available for Direct Offering');
                $table->string('image');
                $table->decimal('price', 15, 2)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('editorials')) {
            Schema::create('editorials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');
                $table->string('title');
                $table->string('author')->nullable();
                $table->text('excerpt')->nullable();
                $table->longText('content')->nullable();
                $table->string('image')->nullable();
                $table->string('video_url')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editorials');
        Schema::dropIfExists('artworks');
    }
};
