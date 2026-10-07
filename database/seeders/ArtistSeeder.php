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
                'photo' => 'https://placehold.co/500x500/181818/ffffff?text=Mustafa+Alatas',
                'artwork_preview' => 'https://placehold.co/800x500/3d5a45/ffffff?text=Mustafa+Artwork',
            ],
            [
                'name' => 'Asep',
                'city' => 'Palembang',
                'bio' => 'Visual artist and illustrator exploring modern South Sumatran aesthetics through distorted typography and graphic deconstruction.',
                'photo' => 'https://placehold.co/500x500/222222/ffffff?text=Asep',
                'artwork_preview' => 'https://placehold.co/800x500/222222/ffffff?text=Asep+Artwork',
            ],
            [
                'name' => 'Asepo',
                'city' => 'Jakarta',
                'bio' => 'Urban street painter blending concrete jungle motifs and subcultural punk elements into wearable textile art.',
                'photo' => 'https://placehold.co/500x500/2c2c2c/ffffff?text=Asepo',
                'artwork_preview' => 'https://placehold.co/800x500/2c2c2c/ffffff?text=Asepo+Artwork',
            ],
            [
                'name' => 'Asepi',
                'city' => 'Padang',
                'bio' => 'Exploring Minangkabau philosophical symbolism through minimalist charcoal strokes and modern apparel silhouettes.',
                'photo' => 'https://placehold.co/500x500/333333/ffffff?text=Asepi',
                'artwork_preview' => 'https://placehold.co/800x500/333333/ffffff?text=Asepi+Artwork',
            ],
        ];

        foreach ($artists as $item) {
            Artist::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
