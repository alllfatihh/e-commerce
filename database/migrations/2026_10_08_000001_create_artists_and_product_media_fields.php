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
        // 1. Create artists table
        if (!Schema::hasTable('artists')) {
            Schema::create('artists', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('city')->nullable();
                $table->text('bio')->nullable();
                $table->string('photo')->nullable();
                $table->string('artwork_preview')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add product images & size chart to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'back_image')) {
                $table->string('back_image')->nullable()->after('image');
            }
            if (!Schema::hasColumn('products', 'footage_image')) {
                $table->string('footage_image')->nullable()->after('back_image');
            }
            if (!Schema::hasColumn('products', 'size_chart_image')) {
                $table->string('size_chart_image')->nullable()->after('footage_image');
            }
        });

        // 3. Add product_id to site_media table for linking footage to specific product
        Schema::table('site_media', function (Blueprint $table) {
            if (!Schema::hasColumn('site_media', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('section');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['back_image', 'footage_image', 'size_chart_image']);
        });

        Schema::table('site_media', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
};
