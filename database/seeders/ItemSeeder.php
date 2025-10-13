<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'The Great Gatsby',
                'publisher_id' => 1,
                'year_published' => 1925,
                'isbn_issn' => '9780743273565',
                'edition' => '1st',
                'call_number' => 'PS3511.I9 G7 1925',
                'item_type_id' => 1,
                'language_id' => 1,
                'remarks' => 'Classic novel',
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'publisher_id' => 2,
                'year_published' => 1960,
                'isbn_issn' => '9780061120084',
                'edition' => '1st',
                'call_number' => 'PS3562.E353 T6 1960',
                'item_type_id' => 1,
                'language_id' => 1,
                'remarks' => 'Pulitzer Prize winner',
            ],
            [
                'title' => '1984',
                'publisher_id' => 3,
                'year_published' => 1949,
                'isbn_issn' => '9780451524935',
                'edition' => '1st',
                'call_number' => 'PR6029.R8 N49 1949',
                'item_type_id' => 1,
                'language_id' => 1,
                'remarks' => 'Dystopian novel',
            ],
            [
                'title' => 'E-Libra',
                'publisher_id' => 4,
                'year_published' => 2024,
                'isbn_issn' => '9789999999999',
                'edition' => '1st',
                'call_number' => 'DEV.ISU.ELIBRA.2024',
                'item_type_id' => 2,
                'language_id' => 1,
                'remarks' => 'ISU Centralized Library Management System. Wala lang, parehas kaming pasuko na HAHAH.',
            ],
        ];

        // Generate 50 random items
        for ($i = 1; $i <= 50; $i++) {
            $year = rand(1950, 2024);
            $isbn = '978' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            $type = rand(1, 3); // assume you have up to 3 item types
            $lang = rand(1, 4); // assume you have 4 languages
            $editions = ['1st', '2nd', '3rd', 'Revised', 'Collector’s'];
            $adjectives = ['Lost', 'Burning', 'Hidden', 'Dark', 'Golden', 'Fallen', 'Forgotten', 'Digital', 'Crystal', 'Electric'];
            $nouns = ['Empire', 'Dreams', 'City', 'Chronicles', 'Library', 'Algorithm', 'Moon', 'Echoes', 'Machine', 'Rebellion'];
            $title = $adjectives[array_rand($adjectives)] . ' ' . $nouns[array_rand($nouns)];

            $data[] = [
                'title' => $title,
                'publisher_id' => 1,
                'year_published' => $year,
                'isbn_issn' => $isbn,
                'edition' => $editions[array_rand($editions)],
                'call_number' => 'CN-' . strtoupper(Str::random(6)),
                'item_type_id' => $type,
                'language_id' => $lang,
                'remarks' => 'Auto-generated seed data for testing purposes.',
            ];
        }

        foreach ($data as $d) {
            Item::create($d);
        }
    }
}
