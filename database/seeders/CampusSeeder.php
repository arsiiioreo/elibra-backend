<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            ['name' => 'Echague Main Campus', 'abbrev' => 'ISU-E', 'address' => 'San Fabian, Echague, Isabela'],
            ['name' => 'Angadanan Campus', 'abbrev' => 'ISU-AC', 'address' => 'Centro 3, Angadanan, Isabela'],
            ['name' => 'Cabagan Campus', 'abbrev' => 'ISU-C', 'address' => 'Centro 3, Angadanan, Isabela'],
            ['name' => 'Jones Campus', 'abbrev' => 'ISU-J', 'address' => 'Centro 3, Angadanan, Isabela'],
        ];

        foreach ($campuses as $campus) {
            Campus::create($campus);
        }
    }
}
