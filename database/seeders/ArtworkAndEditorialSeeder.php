<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Editorial;

class ArtworkAndEditorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mustafa = Artist::where('name', 'Mustafa Alatas')->first();

        if ($mustafa) {
            // Artworks for Mustafa Alatas
            Artwork::updateOrCreate(
                ['artist_id' => $mustafa->id, 'title' => 'Mereka Ulang Ungkapan Indah Leila'],
                [
                    'medium'      => 'Oil on canvas',
                    'dimensions'  => '60x60cm',
                    'year'        => '2024',
                    'status'      => 'Available for Direct Offering',
                    'image'       => 'artwork.jpeg',
                    'price'       => 15000000,
                    'description' => 'Karya eksplorasi fisik cat minyak bertekstur tebal yang merespons dialektika ruang urban dan ketenangan batin.',
                ]
            );

            Artwork::updateOrCreate(
                ['artist_id' => $mustafa->id, 'title' => 'Mereka Ulang Ungkapan Indah Effendi'],
                [
                    'medium'      => 'Oil on canvas',
                    'dimensions'  => '60x60cm',
                    'year'        => '2024',
                    'status'      => 'Available for Direct Offering',
                    'image'       => 'footagebaju2.jpg',
                    'price'       => 15000000,
                    'description' => 'Bagian kedua dari diptych Ungkapan Indah, merefleksikan dekonstruksi figur dan gestur ekspresionis.',
                ]
            );

            Artwork::updateOrCreate(
                ['artist_id' => $mustafa->id, 'title' => 'Figurasi Kuasa Sosial #03'],
                [
                    'medium'      => 'Mixed media on canvas',
                    'dimensions'  => '75x90cm',
                    'year'        => '2023',
                    'status'      => 'Original Archive',
                    'image'       => 'home.jpg',
                    'price'       => 22000000,
                    'description' => 'Arsip kanvas berdimensi besar dengan teknik sapuan kasar dan goresan pigmen mentah.',
                ]
            );

            // Editorial for Mustafa Alatas
            Editorial::updateOrCreate(
                ['artist_id' => $mustafa->id, 'title' => 'Mustafa Alatas Menafsir Kuasa dan Sosial dalam Lukisan Figuratif dan Simbolisme'],
                [
                    'author'      => 'Laksa Dawantara',
                    'excerpt'     => 'Catatan kuratorial mendalam mengenai proses kreatif Mustafa Alatas dalam menerjemahkan friksi sosial ke atas kanvas dan garmen Notisse.',
                    'content'     => "Di balik sapuan kuas yang kasar dan perpaduan pigmen mentah, Mustafa Alatas menyimpan refleksi panjang tentang dinamika kekuasaan dan identitas masyarakat kontemporer.\n\nDalam kolaborasi perdana bersama atelier Notisse, sang seniman asal Temanggung ini mentransformasi gestur visual dari kanvas fisik menuju medium pakaian berpotongan boxy murni katun 16s. Lukisannya tidak sekadar dipindahkan sebagai sablon, melainkan diolah ulang agar serat benang dan tekstur tinta plastisol menyatu harmonis dengan filosofi artistik Notisse.",
                    'image'       => 'masmus.jpeg',
                    'video_url'   => 'video/editorial.mp4',
                ]
            );
        }

        // Add sample artworks for other artists if available
        $asep = Artist::where('name', 'Asep')->first();
        if ($asep) {
            Artwork::updateOrCreate(
                ['artist_id' => $asep->id, 'title' => 'Harmoni Distorsi Urban #01'],
                [
                    'medium'      => 'Acrylic & Charcoal on Linen',
                    'dimensions'  => '70x70cm',
                    'year'        => '2024',
                    'status'      => 'Available for Direct Offering',
                    'image'       => 'home1.jpg',
                    'price'       => 12000000,
                    'description' => 'Eksplorasi arang dan akrilik mengenai kebisingan sudut kota Bandung dalam ritme abstrak.',
                ]
            );

            Editorial::updateOrCreate(
                ['artist_id' => $asep->id, 'title' => 'Eksplorasi Arang dan Simetri Bandung bersama Asep'],
                [
                    'author'      => 'Tim Editorial Notisse',
                    'excerpt'     => 'Wawancara eksklusif bersama Asep tentang eksplorasi media arang murni dan pendekatan minimalis dalam streetwear.',
                    'content'     => 'Asep membedah bagaimana pendekatan monokromatik mampu menghadirkan kedalaman emosional yang lebih kuat dibandingkan warna-warna cerah. Melalui karya terbarunya, ia menantang persepsi umum mengenai seni kontemporer jalanan.',
                    'image'       => 'asep.jpeg',
                    'video_url'   => null,
                ]
            );
        }
    }
}
