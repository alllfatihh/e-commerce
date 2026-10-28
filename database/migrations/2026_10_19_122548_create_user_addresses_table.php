<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('label', 60)->default('Alamat 1');
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('address');
            $table->string('area_id');
            $table->string('area_name')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Migrate existing address data from users table into user_addresses
        $users = DB::table('users')->whereNotNull('address')->where('address', '!=', '')->get();
        foreach ($users as $user) {
            DB::table('user_addresses')->insert([
                'user_id' => $user->id,
                'label' => 'Utama',
                'recipient_name' => $user->name ?? 'Recipient',
                'phone' => $user->phone ?? '',
                'address' => $user->address,
                'area_id' => $user->area_id ?? '',
                'area_name' => $user->address ? Str::limit($user->address, 50) : null,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
