<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FotoshootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Fotoshoot::create([
            'title' => 'Look 1',
            'image' => 'home.jpg',
            'description' => 'Editorial shot 1'
        ]);
        \App\Models\Fotoshoot::create([
            'title' => 'Look 2',
            'image' => 'home.jpg',
            'description' => 'Editorial shot 2'
        ]);
        \App\Models\Fotoshoot::create([
            'title' => 'Look 3',
            'image' => 'home.jpg',
            'description' => 'Editorial shot 3'
        ]);
        \App\Models\Fotoshoot::create([
            'title' => 'Look 4',
            'image' => 'home.jpg',
            'description' => 'Editorial shot 4'
        ]);
    }
}
