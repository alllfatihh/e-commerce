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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, shipped, completed, cancelled
            
            // Midtrans
            $table->string('snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            
            // Biteship
            $table->string('biteship_order_id')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
