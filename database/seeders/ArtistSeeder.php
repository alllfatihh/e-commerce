<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        $artists = [
            [
                'name' => 'Mustafa Alatas',
                'city' => 'Temanggung',
                'bio' => 'Mustafa Alatas is a contemporary visual artist based in Temanggung whose distinct brush strokes and visceral storytelling bring deep cultural narratives into modern street apparel and curated canvas works.',
                'photo' => 'masmus.jpeg',
                'artwork_preview' => 'artwork.jpeg',
            ],
            [
                'name' => 'Asep',
                'city' => 'Palembang',
                'bio' => 'Visual artist and illustrator exploring modern South Sumatran aesthetics through distorted typography and graphic deconstruction.',
                'photo' => 'asep.jpeg',
                'artwork_preview' => 'home1.jpg',
            ],
            [
                'name' => 'Asepo',
                'city' => 'Jakarta',
                'bio' => 'Urban street painter blending concrete jungle motifs and subcultural punk elements into wearable textile art.',
                'photo' => 'asepo.jpeg',
                'artwork_preview' => 'home.jpg',
            ],
            [
                'name' => 'Asepi',
                'city' => 'Padang',
                'bio' => 'Exploring Minangkabau philosophical symbolism through minimalist charcoal strokes and modern apparel silhouettes.',
                'photo' => 'asepi.jpeg',
                'artwork_preview' => 'footage-baju-belakang.jpg',
            ],
            [
                'name' => 'Asepu',
                'city' => 'Bandung',
                'bio' => 'Asepu brings an abstract perspective to everyday urban elements, turning the mundane into compelling art.',
                'photo' => 'masmus.jpeg',
                'artwork_preview' => 'footagebaju2.jpg',
            ],
            [
                'name' => 'Asepa',
                'city' => 'Yogyakarta',
                'bio' => 'Asepa focuses on traditional culture mixed with modern futuristic designs.',
                'photo' => 'asep.jpeg',
                'artwork_preview' => 'artwork.jpeg',
            ],
            [
                'name' => 'Asepit',
                'city' => 'Bali',
                'bio' => 'Asepit translates the energy of the island into dynamic and colorful visual expressions.',
                'photo' => 'asepo.jpeg',
                'artwork_preview' => 'home1.jpg',
            ],
        ];

        foreach ($artists as $item) {
            Artist::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
