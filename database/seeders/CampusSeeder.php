<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Program;
use App\Models\Section;
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
        ];

        foreach ($campuses as $campus) {
            $c = Campus::create($campus);
            $d = Department::create([
                'name' => 'College of Computing Studies, Information and Communication Technology',
                'abbrev' => 'CCSICT',
                'campus_id' => $c->id,
            ]);
            Program::create([
                'name' => 'Bachelor of Science in Information Technology',
                'abbrev' => 'BSIT',
                'department_id' => $d->id,
            ]);
            $b = Branch::create([
                'name' => 'University Library',
                'campus_id' => $c->id,
                'contact_info' => '09123456789',
                'opening_hour' => '08:00:00',
                'closing_hour' => '17:00:00',
            ]);
            Section::create([
                'branch_id' => $b->id,
                'name' => 'General',
            ]);

        }
    }
}
