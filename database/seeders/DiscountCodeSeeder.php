<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [
            [
                'code' => 'NOTISSE',
                'description' => 'Diskon Pembelian Pertama Notisse (10%)',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 0,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'is_active' => true,
            ],
            [
                'code' => 'NOTISSE10',
                'description' => 'Diskon Spesial Apparel 10%',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 0,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'is_active' => true,
            ],
            [
                'code' => 'NOTISSE50',
                'description' => 'Diskon Eksklusif Koleksi 50%',
                'type' => 'percentage',
                'value' => 50,
                'min_order_amount' => 0,
                'max_discount_amount' => 150000,
                'usage_limit' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'POTONGAN50K',
                'description' => 'Potongan Langsung Rp 50.000 (Min. Belanja Rp 250.000)',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 250000,
                'max_discount_amount' => null,
                'usage_limit' => 50,
                'is_active' => true,
            ],
        ];

        foreach ($codes as $data) {
            DiscountCode::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
