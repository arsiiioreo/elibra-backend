<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            //Main Campus
            ['name' => 'Echague Main Campus', 'abbrev' => 'ISU-E', 'address' => 'San Fabian, Echague, Isabela'],

            //Satellite Campuses
            ['name' => 'Angadanan Campus', 'abbrev' => 'ISU-AC', 'address' => 'Centro 3, Angadanan, Isabela'],
            ['name' => 'Cabagan Campus', 'abbrev' => 'ISU-C', 'address' => 'Ugad, Cabagan, Isabela'],
            ['name' => 'Cauayan Campus', 'abbrev' => 'ISU-CY', 'address' => 'Don Jose Canciller Aveneu, Cauayan City, Isabela'],
            ['name' => 'Ilagan Campus', 'abbrev' => 'ISU-I', 'address' => 'Baligatan, Ilagan City, Isabela'],
            ['name' => 'Jones Campus', 'abbrev' => 'ISU-J', 'address' => 'Canauayan, Jones, Isabela'],
            ['name' => 'Roxas Campus', 'abbrev' => 'ISU-R', 'address' => 'Rang-ayan, Roxas, Isabela'],
            ['name' => 'San Mateo Campus', 'abbrev' => 'ISU-SM', 'address' => 'San Andres, San Mateo, Isabela'],

            // Extension Campuses
            ['name' => 'San Mariano Campus', 'abbrev' => 'ISU-SMC', 'address' => 'Maligaya, San Mariano, Isabela'],
            ['name' => 'Santiago Extension', 'abbrev' => 'ISU-SX', 'address' => 'Villasis, Santiago City, Isabela'],
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

            $d_agri = Department::create([
                'name' => 'College of Agriculture',
                'abbrev' => 'CA',
                'campus_id' => $c->id,
            ]);
            Program::create([
                'name' => 'Bachelor of Science in Agriculture',
                'abbrev' => 'BSA',
                'department_id' => $d_agri->id,
            ]);

            $d_educ = Department::create([
                'name' => 'College of Education',
                'abbrev' => 'CED',
                'campus_id' => $c->id,
            ]);
            Program::create([
                'name' => 'Bachelor of Science in Education Major in English',
                'abbrev' => 'BSE-English',
                'department_id' => $d_educ->id,
            ]);

            Branch::create([
                'name' => 'University Library',
                'campus_id' => $c->id,
                'contact_info' => '09123456789',
                'opening_hour' => '08:00:00',
                'closing_hour' => '17:00:00',
            ]);
        }
    }
}
