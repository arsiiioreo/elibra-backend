<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CampusSeeder::class,
            HolidaysSeeder::class,
            PublisherSeeder::class,
            LanguageSeeder::class,
            LoanModesSeeder::class,
            PatronTypeSeeder::class,
            SuperAdminSeeder::class,

            // ItemSeeder::class,
            TestUser::class,
        ]);
    }
}
