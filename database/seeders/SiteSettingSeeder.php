<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
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
            ['key' => 'contact_email', 'value' => 'hello@notisse.com'],
            ['key' => 'contact_phone', 'value' => '+62 811 2233 4455'],
            ['key' => 'instagram_link', 'value' => '#'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
