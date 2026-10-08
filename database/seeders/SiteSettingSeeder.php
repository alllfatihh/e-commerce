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
            ['key' => 'marquee_text', 'value' => 'NOTISSE SPRING 2026 - DISCOVER THE LATEST COLLECTION'],
            ['key' => 'footer_description', 'value' => 'Notisse is a concept brand exploring the intersection of modern aesthetics and raw street culture. Based in Indonesia, curating global narratives.'],
            ['key' => 'contact_email', 'value' => 'hello@notisse.com'],
            ['key' => 'contact_phone', 'value' => '+62 811 2233 4455'],
            ['key' => 'instagram_link', 'value' => '#'],
        ];

        foreach ($settings as $setting) {
            \App\Models\SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
