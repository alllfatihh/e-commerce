<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductStock;

class ProductStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $defaultStocks = [
            'S'  => 10,
            'M'  => 15,
            'L'  => 12,
            'XL' => 8,
        ];

        foreach ($products as $product) {
            foreach ($defaultStocks as $size => $stock) {
                ProductStock::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'size'       => $size,
                    ],
                    [
                        'stock'      => $stock,
                    ]
                );
            }
        }
    }
}
