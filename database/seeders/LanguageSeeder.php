<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'English'],
            ['name' => 'Filipino'],
            ['name' => 'Spanish'],
            ['name' => 'Italian'],
            ['name' => 'Latin'],
            ['name' => 'French'],
            ['name' => 'Nihongo'],
            ['name' => 'Korean'],
            ['name' => 'German'],
            ['name' => 'Mandarin'],
            ['name' => 'Bahasa'],
            ['name' => 'Waray'],
            ['name' => 'Latin'],
            ['name' => 'Hebrew'],
            ['name' => 'Ilokano'],
        ];

        foreach ($data as $d) {
            Language::create($d);
        }
    }
}
