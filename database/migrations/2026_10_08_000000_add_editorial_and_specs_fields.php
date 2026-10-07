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
            if (!Schema::hasColumn('products', 'specs')) {
                $table->text('specs')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'artist')) {
                $table->string('artist')->nullable()->after('specs');
            }
        });

        Schema::table('fotoshoots', function (Blueprint $table) {
            if (!Schema::hasColumn('fotoshoots', 'artist_name')) {
                $table->string('artist_name')->nullable()->after('title');
            }
            if (!Schema::hasColumn('fotoshoots', 'medium')) {
                $table->string('medium')->nullable()->after('artist_name');
            }
            if (!Schema::hasColumn('fotoshoots', 'year')) {
                $table->string('year')->nullable()->after('medium');
            }
            if (!Schema::hasColumn('fotoshoots', 'status')) {
                $table->string('status')->default('Available for Offering')->after('year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['specs', 'artist']);
        });

        Schema::table('fotoshoots', function (Blueprint $table) {
            $table->dropColumn(['artist_name', 'medium', 'year', 'status']);
        });
    }
};
