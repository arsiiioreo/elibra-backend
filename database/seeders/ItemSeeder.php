<?php

namespace Database\Seeders;

use App\Http\Controllers\AccessionsController;
use App\Models\Acquisition;
use App\Models\Audio;
use App\Models\Author;
use App\Models\Book;
use App\Models\Dissertation;
use App\Models\Electronic;
use App\Models\Item;
use App\Models\Campus;
use App\Models\ItemAuthors;
use App\Models\Newspaper;
use App\Models\Periodical;
use App\Models\Serial;
use App\Models\Thesis;
use App\Models\Vertical;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $campusIds = Campus::pluck('id')->toArray();
            if(empty($campusIds)){
                dd('No campuses found. Please seed campuses first.');
            }   
            
        // Generate 50 random items
        for ($i = 1; $i < 20; $i++) {
            $isbn = '978'.str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            $type = rand(1, 9); // assume you have up to 3 item types
            $lang = rand(1, 15); // assume you have 4 languages
            $file_size = ['GB', 'MB'];
            $file_type = ['MP3', 'M4A'];
            $editions = ['1st', '2nd', '3rd', 'Revised', 'Collector’s'];
            $organizations = ['International', 'Global', 'University'];
            $adjectives = [
                'Lost', 'Burning', 'Hidden', 'Dark', 'Golden', 'Fallen', 'Forgotten', 'Digital', 'Crystal', 'Electric',
                'Eternal', 'Silent', 'Broken', 'Shattered', 'Neon', 'Quantum', 'Sacred', 'Violet', 'Iron', 'Phantom',
                'Celestial', 'Frozen', 'Mirrored', 'Scarlet', 'Fractal', 'Arcane', 'Gravity', 'Infinite', 'Synthetic',
                'Orbital', 'Astral', 'Radiant', 'Wicked', 'Prime', 'Parallel', 'Nocturnal', 'Vortex', 'Encrypted',
                'Haunted', 'Decoded', 'Static', 'Ghosted', 'Shadowed', 'Forgotten', 'Emerald', 'Obsidian', 'Solar',
                'Lunar', 'Stormborne', 'Silent', 'Ethereal', 'Ancient', 'Boundless', 'Distant', 'Chaotic', 'Harmonic',
                'Spectral', 'Cobalt', 'Infernal', 'Mystic', 'Primal', 'Blazing', 'Ivory', 'Onyx', 'Twilight', 'Ambient',
                'Hidden', 'Timeless', 'Lucid', 'Arcadian', 'Radiant', 'Bound', 'Tempest', 'Gilded', 'Hallowed',
                'Encrypted', 'Encrypted', 'Feral', 'Majestic', 'Infinite', 'Pale', 'Serene', 'Crimson', 'Azure',
                'Sapphire', 'Vast', 'Abyssal', 'Binary', 'Encrypted', 'Feral', 'Eclipsed', 'Quantum', 'Everlasting',
                'Forged', 'Stormforged', 'Pulsebound', 'Runic', 'Holographic',
            ];
            $copies = rand(1, 10);

            $nouns = [
                'Empire', 'Dreams', 'City', 'Chronicles', 'Library', 'Algorithm', 'Moon', 'Echoes', 'Machine', 'Rebellion',
                'Kingdoms', 'Horizons', 'Machines', 'Legends', 'Rituals', 'Fragments', 'Signals', 'Shores', 'Hollows',
                'Archives', 'Miracles', 'Circuits', 'Paradox', 'Odyssey', 'Spectrum', 'Syndicate', 'Catalysts',
                'Prophecy', 'Nebula', 'Voyagers', 'Realm', 'Domain', 'Construct', 'Pulse', 'Network', 'Archive',
                'Portal', 'Uprising', 'Dynasty', 'Citadel', 'Voyage', 'Galaxy', 'Storms', 'Stars', 'Sins', 'Artifacts',
                'Ruins', 'Horizons', 'Origins', 'Genesis', 'Vaults', 'Lineage', 'Tides', 'Nomads', 'Wanderers',
                'Keepers', 'Dreamscape', 'Manuscripts', 'Symphony', 'Equinox', 'Sanctum', 'Legacy', 'Fables',
                'Covenant', 'Spectrum', 'Horizons', 'Pillars', 'Threshold', 'Temple', 'Hymn', 'Reflections',
                'Mirage', 'Reverie', 'Aeons', 'Cycles', 'Blueprints', 'Constructs', 'Designs', 'Eclipse',
                'Reactor', 'Circuitry', 'Codex', 'Monolith', 'Grid', 'Matrix', 'Pulsewave', 'Convergence',
                'Voyage', 'Fortress', 'Harbor', 'Frontier', 'Origin', 'Cascade', 'Rift', 'Verse', 'Continuum',
            ];

            $firstNames = [
                'Adrian', 'Aiden', 'Althea', 'Alexa', 'Amara', 'Andre', 'Angela', 'Arvin', 'Asher', 'Aubrey',
                'Bea', 'Benjamin', 'Bianca', 'Bryle', 'Brent', 'Caleb', 'Celine', 'Charles', 'Cheska', 'Cyrus',
                'Darian', 'Daniel', 'Danica', 'Darren', 'Dave', 'Denise', 'Devon', 'Dylan', 'EJ', 'Eleanor',
                'Elliot', 'Elijah', 'Ella', 'Eloise', 'Enzo', 'Ethan', 'Felix', 'Finn', 'Frances', 'Franco',
                'Gabriel', 'Gavin', 'Gwen', 'Hailey', 'Hannah', 'Harold', 'Harvey', 'Hazel', 'Ian', 'Ira',
                'Isabella', 'Isaac', 'Ivy', 'Jay', 'Jace', 'Jacob', 'Jana', 'Jared', 'Jasmine', 'Jax',
                'Jillian', 'Jiro', 'Jude', 'Kael', 'Kai', 'Kaira', 'Keith', 'Kenji', 'Kevin', 'Kyla',
                'Lance', 'Lara', 'Lawrence', 'Lea', 'Liam', 'Luca', 'Lyra', 'Macy', 'Marco', 'Marcus',
                'Mariel', 'Mason', 'Matteo', 'Mia', 'Mikhail', 'Miles', 'Nathan', 'Nicole', 'Noah', 'Nova',
                'Owen', 'Olivia', 'Parker', 'Phoebe', 'Rafael', 'Raine', 'Reese', 'Reign', 'Riley', 'Rocco',
                'Sage', 'Sam', 'Samantha', 'Santino', 'Selene', 'Sean', 'Sky', 'Sophia', 'Theo', 'Troy',
                'Van', 'Vernon', 'Vince', 'Winter', 'Wyatt', 'Yana', 'Yuri', 'Zach', 'Zara', 'Zeke',
            ];

            $lastNames = [
                'Aguilar', 'Alvarez', 'Anderson', 'Aquino', 'Aragon', 'Bautista', 'Belmont', 'Blackwell', 'Bolton', 'Brooks',
                'Calderon', 'Campbell', 'Castillo', 'Cortez', 'Cruz', 'Davenport', 'Delgado', 'Domingo', 'Donovan', 'Durham',
                'Edwards', 'Estrada', 'Evans', 'Fernandez', 'Figueroa', 'Fletcher', 'Flores', 'Ford', 'Gaines', 'Garcia',
                'Gomez', 'Gonzales', 'Goodwin', 'Grant', 'Greyson', 'Guerrero', 'Hale', 'Hall', 'Hamilton', 'Hernandez',
                'Hoffman', 'Howard', 'Ibarra', 'Ingram', 'Jacinto', 'Javier', 'Johnson', 'Juarez', 'Kennedy', 'Knight',
                'Larsen', 'Lawson', 'Lim', 'Lopez', 'Luna', 'Madrigal', 'Manalo', 'Marquez', 'Martin', 'Mendez',
                'Miller', 'Montgomery', 'Navarro', 'Nelson', 'Nieves', 'Nolasco', 'Ocampo', 'Oliver', 'Ortiz', 'Owens',
                'Pacheco', 'Parker', 'Patterson', 'Perez', 'Pineda', 'Reeves', 'Reyes', 'Rodriguez', 'Romero', 'Roxas',
                'Salazar', 'Santos', 'Sawyer', 'Scott', 'Silva', 'Simpson', 'Smith', 'Soriano', 'Steele', 'Sullivan',
                'Thompson', 'Torres', 'Turner', 'Valdez', 'Vega', 'Velasco', 'Villanueva', 'Ward', 'Watson', 'Williams',
                'Wilson', 'Young', 'Zamora', 'Zhang', 'Zimmerman',
            ];

            $title = $adjectives[array_rand($adjectives)].' '.$nouns[array_rand($nouns)];

            $item = Item::create([
                'title' => $title,
                /*UNDER REVIEW ADJUSTMENT*/
                // 'publisher_id' => rand(1, 4),
                // 'year_published' => $year,
                // 'isbn_issn' => $isbn,
                // 'edition' => $editions[array_rand($editions)],
                // 'call_number' => 'CN-' . strtoupper(Str::random(6)),
                'publisher_id' => rand(1, 5),
                'year_published' => rand(1950, 2024),
                'call_number' => 'CN-'.strtoupper(Str::random(6)),
                'item_type_id' => $type,
                'language_id' => $lang,
                'campus_id' => $campusIds[array_rand($campusIds)],
                'remarks' => 'Auto-generated seed data for testing purposes.',
            ]);
            
            if ($item->item_type_id == 1) {
                Book::create([
                    'item_id' => $item->id,
                    'isbn_issn' => $isbn,
                    'pages' => rand(25, 500),
                    'edition' => $editions[array_rand($editions)],
                    'categories_id' => rand(1, 9),
                ]);
            } elseif ($item->item_type_id == 2) {
                Thesis::create([
                    'item_id' => $item->id,
                    'abstract' => "This research is generated for testing purposes only, this doesn't exist.",
                    'advisor' => $firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)],
                    'program_id' => 1,
                ]);
            } elseif ($item->item_type_id == 3) {
                Dissertation::create([
                    'item_id' => $item->id,
                    'abstract' => "This research is generated for testing purposes only, this doesn't exist.",
                    'advisor' => $firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)],
                    'program_id' => 1,
                ]);
            } elseif ($item->item_type_id == 4) {
                Audio::create([
                    'item_id' => $item->id,
                    'format' => $file_type[array_rand($file_type)],
                    'duration' => rand(1, 9).':'.rand(00, 59),
                    'producer' => $firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)],
                ]);
            } elseif ($item->item_type_id == 5) {
                Serial::create([
                    'item_id' => $item->id,
                    'volume' => 'I',
                    'isbn_issn' => $isbn,
                    'issue' => 'in. 2025',
                    'pages' => rand(20, 500),
                ]);
            } elseif ($item->item_type_id == 6) {
                Periodical::create([
                    'item_id' => $item->id,
                    'volume' => 'I',
                    'isbn_issn' => $isbn,
                    'issue' => rand(1, 100),
                    'pages' => rand(1, 100),
                ]);
            } elseif ($item->item_type_id == 7) {
                Electronic::create([
                    'item_id' => $item->id,
                    'file_size' => rand(1, 300).$file_size[array_rand($file_size)],
                    'isbn_issn' => $isbn,
                    'access_url' => null,
                ]);
            } elseif ($item->item_type_id == 8) {
                Vertical::create([
                    'item_id' => $item->id,
                    'organization' => $organizations[array_rand($organizations)].' of '.$adjectives[array_rand($adjectives)].' '.$nouns[array_rand($nouns)],
                    'location' => 'Philippines',
                    'notes' => 'Generated for testing.',
                ]);
            } elseif ($item->item_type_id == 9) {
                Newspaper::create([
                    'item_id' => $item->id,
                    'date' => now(),
                    'edition' => $editions[array_rand($editions)],
                    'pages' => rand(1, 25),
                ]);
            }

            AccessionsController::accession($item, $copies); // Acquisition and Accession record here
        }
    }
}
