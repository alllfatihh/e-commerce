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
        Schema::table('products', function (Blueprint $table) {
            $table->string('model_gender', 50)->nullable()->after('artist');
            $table->unsignedSmallInteger('model_height')->nullable()->after('model_gender');
            $table->unsignedSmallInteger('model_weight')->nullable()->after('model_height');
            $table->string('model_size', 50)->nullable()->after('model_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['model_gender', 'model_height', 'model_weight', 'model_size']);
        });
    }
};
