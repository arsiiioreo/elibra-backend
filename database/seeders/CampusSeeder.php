<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Section;
use App\Models\Sections;
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
            ['campus' => 'Echague Main Campus', 'abbrev' => 'ISU-E', 'address' => 'San Fabian, Echague, Isabela', 'is_active' => '1'],
            ['campus' => 'Angadanan Campus', 'abbrev' => 'ISU-AC', 'address' => 'Centro 3, Angadanan, Isabela', 'is_active' => '0'],
            ['campus' => 'Cabagan Campus', 'abbrev' => 'ISU-C', 'address' => 'Cabagan, Isabela', 'is_active' => '0'],
            ['campus' => 'Cauayan Campus', 'abbrev' => 'ISU-CYN', 'address' => 'Cauayan, Isabela', 'is_active' => '0'],
            ['campus' => 'Ilagan Campus', 'abbrev' => 'ISU-I', 'address' => 'Ilagan, Isabela', 'is_active' => '0'],
        ];

        foreach ($campuses as $campus) {
            Campus::create($campus);
        }
        
        Section::create([
            'name' => 'Serials',
            'campus_id' => '1',
        ]);
        
        Section::create([
            'name' => 'Thesis',
            'campus_id' => '1',
        ]);
    }
}
