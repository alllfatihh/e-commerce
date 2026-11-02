<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editorials', function (Blueprint $table) {
            $table->string('image_2')->nullable()->after('image');
            $table->longText('content_2')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('editorials', function (Blueprint $table) {
            $table->dropColumn(['image_2', 'content_2']);
        });
    }
};
