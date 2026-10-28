<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;

class ProductStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $defaultStocks = [
            'S' => 10,
            'M' => 15,
            'L' => 12,
            'XL' => 8,
        ];

        foreach ($products as $product) {
            foreach ($defaultStocks as $size => $stock) {
                ProductStock::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'size' => $size,
                    ],
                    [
                        'stock' => $stock,
                    ]
                );
            }
        }
    }
}
