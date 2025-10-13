<?php

namespace Database\Seeders;

use App\Models\User;
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
            CategorySeeder::class,
            DepartmentSeeder::class,
            HolidaysSeeder::class,
            PublisherSeeder::class,
            ItemTypeSeeder::class,
            LanguageSeeder::class,
            LoanModesSeeder::class,
            ItemSeeder::class,
            PatronTypeSeeder::class,
            SuperAdminSeeder::class,
            TestUser::class,
        ]);
    }
}
