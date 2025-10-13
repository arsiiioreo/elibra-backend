<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Penguin Random House'],
            ['name' => 'HarperCollins'],
            ['name' => 'Simon & Schuster'],
            ['name' => 'Hachette Book Group'],
            ['name' => 'Macmillan Publishers'],
        ];

        foreach ($data as $d) {
            Publisher::create($d);
        }
    }
}
