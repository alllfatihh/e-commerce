<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fotoshoot;

class FotoshootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fotoshoot::truncate();

        Fotoshoot::create([
            'title'       => 'Mereka Ulang Ungkapan Indah Leila',
            'artist_name' => 'Mustafa Alatas',
            'medium'      => 'Oil on canvas • 60x60cm',
            'year'        => '2026',
            'status'      => 'Available for Offering',
            'image'       => 'footagebaju2.jpg',
            'description' => 'Karya seni rupa eksplorasi emosi dan dialog visual antara manusia dan tekstil karya Mustafa Alatas, seniman asal Temanggung.'
        ]);

        Fotoshoot::create([
            'title'       => 'Masa Depan Siluet Monokrom',
            'artist_name' => 'Asep Suhendar',
            'medium'      => 'Mixed Media on Linen • 80x100cm',
            'year'        => '2026',
            'status'      => 'Available for Offering',
            'image'       => 'footage-baju-belakang.jpg',
            'description' => 'Eksperimen medium campuran yang menangkap dekonstruksi pakaian kerja modern dan estetika brutalism.'
        ]);

        Fotoshoot::create([
            'title'       => 'Ritual Pagi Hari di Ruang Kedap',
            'artist_name' => 'Raden Asepo',
            'medium'      => 'Archival Pigment Print • 50x70cm',
            'year'        => '2026',
            'status'      => 'Display Only',
            'image'       => 'footage-baju.jpg',
            'description' => 'Dokumentasi editorial visual studio Notisse, menangkap esensi ketenangan dan material mentah katun murni.'
        ]);

        Fotoshoot::create([
            'title'       => 'Dekonstruksi Ruang & Waktu',
            'artist_name' => 'Asepi Maulana',
            'medium'      => 'Acrylic & Charcoal on Raw Canvas • 100x120cm',
            'year'        => '2026',
            'status'      => 'Available for Offering',
            'image'       => 'home2.png',
            'description' => 'Penjelajahan tekstur mentah dan ketidakteraturan simetri dalam seri editorial Notisse Fashion & Art Brand.'
        ]);
    }
}
