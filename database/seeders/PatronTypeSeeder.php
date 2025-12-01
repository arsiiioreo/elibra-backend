<?php

namespace Database\Seeders;

use App\Models\PatronTypes;
use Illuminate\Database\Seeder;

class PatronTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'key' => 'faculty',
                'name' => 'Faculty',
                'description' => 'Faculties Group',
            ],
            [
                'key' => 'student',
                'name' => 'Student',
                'description' => 'Students Group',
            ],
            [
                'key' => 'guest',
                'name' => 'Guest',
                'description' => 'Patrons outside of the Isabela State University System',
            ],
        ];

        foreach ($data as $d) {
            PatronTypes::create($d);
        }
    }
}
