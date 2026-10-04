<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'Raja Diamuk Massa Tee V1',
            'price' => '250000',
            'image' => 'footage-baju.jpg',
            'description' => 'Heavyweight 24s Cotton Combed construction. High-density screenprinted artwork on back and chest.'
        ]);

        \App\Models\Product::create([
            'name' => 'Muscle Tank V2 - Black',
            'price' => '180000',
            'image' => 'footage-baju.jpg',
            'description' => 'Premium muscle tank perfect for everyday wear.'
        ]);

        \App\Models\Product::create([
            'name' => 'Relaxed Tailored Shirt V1',
            'price' => '320000',
            'image' => 'footage-baju.jpg',
            'description' => 'A relaxed, oversized fit tailored shirt made from breathable cotton.'
        ]);

        \App\Models\Product::create([
            'name' => 'Collarless Leather Blazer',
            'price' => '650000',
            'image' => 'footage-baju.jpg',
            'description' => 'Sophisticated collarless leather blazer for a sleek and modern look.'
        ]);
    }
}
