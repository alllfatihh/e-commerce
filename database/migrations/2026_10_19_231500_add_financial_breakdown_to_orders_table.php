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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 14, 2)->default(0)->after('destination_area_id');
            $table->decimal('shipping_cost', 14, 2)->default(0)->after('subtotal');
            $table->decimal('discount_amount', 14, 2)->default(0)->after('shipping_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'shipping_cost', 'discount_amount']);
        });
    }
};
