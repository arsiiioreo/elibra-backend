<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "date" => "2018-11-01 00:00:00",
                "name" => "All Saints Day"
            ],
            [
                "date" => "2020-11-30 00:00:00",
                "name" => "Bonifacio Day"
            ],
            [
                "date" => "2020-11-02 00:00:00",
                "name" => "All Souls Day"
            ],
            [
                "date" => "2021-01-01 00:00:00",
                "name" => "New Year"
            ],
            [
                "date" => "2021-02-25 00:00:00",
                "name" => "EDSA Day"
            ],
            [
                "date" => "2021-02-12 00:00:00",
                "name" => "Chinese New Year"
            ],
            [
                "date" => "2021-11-01 00:00:00",
                "name" => "All Saint Day"
            ],
            [
                "date" => "2021-04-09 00:00:00",
                "name" => "Araw ng Kagitingan"
            ],
            [
                "date" => "2021-12-25 00:00:00",
                "name" => "Christmas Day"
            ],
            [
                "date" => "2021-06-12 00:00:00",
                "name" => "Independence Day"
            ],
            [
                "date" => "2021-07-20 00:00:00",
                "name" => "Edll Adha"
            ],
            [
                "date" => "2021-12-25 00:00:00",
                "name" => "Christmas Day"
            ],
            [
                "date" => "2022-02-25 00:00:00",
                "name" => "EDSA Day"
            ],
            [
                "date" => "2022-12-25 00:00:00",
                "name" => "Christmas Day"
            ],
            [
                "date" => "2022-08-19 00:00:00",
                "name" => "Quezon City Day"
            ]
        ];

        foreach ($data as $d) {
            Holiday::create($d);
        }
    }
}
