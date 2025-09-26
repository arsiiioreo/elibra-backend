<?php

namespace Database\Seeders;

use App\Models\PatronTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                "name" => "Faculty",
                "description" => "Faculties Group",
                "can_reserve" => true,
                "reservation_limit" => 0
            ],
            [
                "name" => "Student",
                "description" => "Students Group",
                "can_reserve" => true,
                "reservation_limit" => 1
            ],
            [
                "name" => "Guest",
                "description" => "Patrons out of the Isabela State University",
                "can_reserve" => false,
                "reservation_limit" => 1
            ],
        ];

        foreach ($data as $d) {
            PatronTypes::create($d);
        }
    }
}
