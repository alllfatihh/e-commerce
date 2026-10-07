<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::updateOrCreate(
            ['id' => 1],
            [
                'name'        => 'Raja Diamuk Massa Tee V1',
                'price'       => '250000',
                'image'       => 'footage-baju.jpg',
                'description' => 'Heavyweight 24s Cotton Combed construction. High-density screenprinted artwork on back and chest celebrating contemporary urban folklore.',
                'specs'       => '100% Heavyweight 24s Cotton Combed (220 GSM) • Boxy Drop Shoulder Fit • Plastisol High-Density Screenprint • Pre-shrunk Fabric',
                'artist'      => 'Mustafa Alatas'
            ]
        );

        Product::updateOrCreate(
            ['id' => 2],
            [
                'name'        => 'Muscle Tank V2 - Black',
                'price'       => '180000',
                'image'       => 'footage-baju.jpg',
                'description' => 'Premium muscle tank crafted for relaxed everyday layering and brutalist silhouettes.',
                'specs'       => 'Cotton Rib Knit • Raw Hem Cut • Tailored Armholes • Pre-washed for Soft Vintage Feel',
                'artist'      => 'Asep Suhendar'
            ]
        );

        Product::updateOrCreate(
            ['id' => 3],
            [
                'name'        => 'Relaxed Tailored Shirt V1',
                'price'       => '320000',
                'image'       => 'footage-baju.jpg',
                'description' => 'A relaxed, oversized fit tailored shirt made from breathable Japanese poplin cotton.',
                'specs'       => 'Premium Poplin Cotton • Mother of Pearl Buttons • Relaxed Drape Silhouette • Cuban Collar',
                'artist'      => 'Raden Asepo'
            ]
        );

        Product::updateOrCreate(
            ['id' => 4],
            [
                'name'        => 'Collarless Leather Blazer',
                'price'       => '650000',
                'image'       => 'footage-baju.jpg',
                'description' => 'Sophisticated collarless leather blazer for a sleek, sculptural and modern look.',
                'specs'       => 'Vegan Full-Grain Leather • Cupro Satin Lining • Minimalist Hidden Magnetic Fasteners • Structured Shoulders',
                'artist'      => 'Mustafa Alatas'
            ]
        );
    }
}
