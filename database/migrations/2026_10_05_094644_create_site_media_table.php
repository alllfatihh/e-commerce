<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_media', function (Blueprint $table) {
            $table->id();
            $table->string('section');       // carousel | footage | fotoshoot
            $table->string('key')->nullable(); // slide_1, slide_2, footage_front, etc.
            $table->string('image');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Seed dengan data default (gambar yang sudah ada di public/)
        DB::table('site_media')->insert([
            // Hero Carousel
            ['section' => 'carousel', 'key' => 'slide_1', 'image' => 'home.jpg',   'title' => 'Hero Slide 1', 'description' => null, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section' => 'carousel', 'key' => 'slide_2', 'image' => 'home1.jpg',  'title' => 'Hero Slide 2', 'description' => null, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['section' => 'carousel', 'key' => 'slide_3', 'image' => 'home2.png',  'title' => 'Hero Slide 3', 'description' => null, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            // Footage / Look Section
            ['section' => 'footage',  'key' => 'footage_1', 'image' => 'footage-baju.jpg',         'title' => 'Front View',   'description' => null, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section' => 'footage',  'key' => 'footage_2', 'image' => 'footage-baju-belakang.jpg','title' => 'Back Graphic', 'description' => null, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['section' => 'footage',  'key' => 'footage_3', 'image' => 'footagebaju2.jpg',         'title' => 'Look Detail',  'description' => null, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_media');
    }
};
