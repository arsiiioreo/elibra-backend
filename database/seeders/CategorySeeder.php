<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Textbook'],
            ['name' => 'English'],
            ['name' => 'Math'],
            ['name' => 'Science'],
            ['name' => 'Encyclopedia'],
            ['name' => 'Filipiniana'],
            ['name' => 'Novel'],
            ['name' => 'General'],
            ['name' => 'References'],
        ];

        foreach ($data as $d) {
            Category::create($d);
        }
    }
}
