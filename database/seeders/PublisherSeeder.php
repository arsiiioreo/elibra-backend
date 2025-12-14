<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publisherNames = ['Penguin', 'HarperCollins', 'Simon & Schuster', 'Hachette', 'Macmillan', 'Scholastic', 'Wiley', 'Oxford', 'Cambridge', 'Elsevier', 'Pearson', 'Springer', 'McGraw-Hill', 'Cengage', 'Bloomsbury'];
        $companyTags = ['Publishing Inc.', 'Publishing House', 'Books & Media', 'Academic Press', 'Global Publishing', 'Educational Publishing', 'Media Group', 'Press', 'Knowledge Corp.', 'Learning Solutions'];
        $streetNames = ['Broadway', 'Madison Ave', 'Lexington Ave', '5th Avenue', 'Park Avenue', 'University Road', 'Knowledge Street', 'Library Lane', 'Scholars Way', 'Innovation Drive'];
        $cities = ['New York, NY',    'Boston, MA',    'Cambridge, MA',    'Oxford, UK',    'London, UK',    'San Francisco, CA',    'Toronto, ON',    'Sydney, AU',    'Singapore',    'Manila, PH'];

        for ($i = 1; $i <= 20; $i++) {
            Publisher::create([
                'name' => $publisherNames[array_rand($publisherNames)].' '.$companyTags[array_rand($companyTags)],
                'address' => rand(100, 999).' '.$streetNames[array_rand($streetNames)].', '.$cities[array_rand($cities)],
            ]);
        }
    }
}
