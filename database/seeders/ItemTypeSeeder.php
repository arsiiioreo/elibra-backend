<?php

namespace Database\Seeders;

use App\Models\ItemTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Book'],
            ['name' => 'Thesis'],
            ['name' => 'Dissertation'],
            ['name' => 'Audio'],
            ['name' => 'Serial'],
            ['name' => 'Periodical'],
            ['name' => 'Electronic'],
            ['name' => 'Vertical File'],
            ['name' => 'Newspaper Clipping'],
        ];

        foreach ($data as $d) {
            ItemTypes::create($d);
        }
    }
}
