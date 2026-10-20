<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'marquee_text', 'value' => 'NOTISSE WORLDWIDE SHIPPING'],
            ['key' => 'contact_workshop', 'value' => 'Jl Mangga Dua, Desa Sukapura, DayeuhKolot, Bandung.'],
            ['key' => 'contact_email', 'value' => 'notissebrand@gmail.com'],
            ['key' => 'contact_phone', 'value' => '+62 856 0771 4704'],
            ['key' => 'instagram_link', 'value' => 'https://instagram.com/notisse'],
            ['key' => 'site_description', 'value' => 'NOTISSE IS AN ALTERNATIVE MOVEMENT IN ART EXHIBITION. WOVEN INTO THE FABRIC OF EVERYDAY LIFE. SO ART BECOMES PART OF DAILY ACTIVITY NOT A SEPARATE OCCASION.'],
        ];

        foreach ($settings as $setting) {
            \App\Models\SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
