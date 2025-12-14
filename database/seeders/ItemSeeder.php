<?php

namespace Database\Seeders;

use App\Http\Controllers\ExtendedBibliography;
use App\Models\Accessions;
use App\Models\Acquisition;
use App\Models\AcquisitionLine;
use App\Models\Author;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemAuthors;
use App\Models\Language;
use App\Models\Librarian;
use App\Models\Publisher;
use App\Models\Section;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Author
        $firstNames = ['Adrian', 'Aiden', 'Althea', 'Alexa', 'Amara', 'Andre', 'Angela', 'Arvin', 'Asher', 'Aubrey', 'Bea', 'Benjamin', 'Bianca', 'Bryle', 'Brent', 'Caleb', 'Celine', 'Charles', 'Cheska', 'Cyrus', 'Darian', 'Daniel', 'Danica', 'Darren', 'Dave', 'Denise', 'Devon', 'Dylan', 'EJ', 'Eleanor', 'Elliot', 'Elijah', 'Ella', 'Eloise', 'Enzo', 'Ethan', 'Felix', 'Finn', 'Frances', 'Franco', 'Gabriel', 'Gavin', 'Gwen', 'Hailey', 'Hannah', 'Harold', 'Harvey', 'Hazel', 'Ian', 'Ira', 'Isabella', 'Isaac', 'Ivy', 'Jay', 'Jace', 'Jacob', 'Jana', 'Jared', 'Jasmine', 'Jax', 'Jillian', 'Jiro', 'Jude', 'Kael', 'Kai', 'Kaira', 'Keith', 'Kenji', 'Kevin', 'Kyla', 'Lance', 'Lara', 'Lawrence', 'Lea', 'Liam', 'Luca', 'Lyra', 'Macy', 'Marco', 'Marcus', 'Mariel', 'Mason', 'Matteo', 'Mia', 'Mikhail', 'Miles', 'Nathan', 'Nicole', 'Noah', 'Nova', 'Owen', 'Olivia', 'Parker', 'Phoebe', 'Rafael', 'Raine', 'Reese', 'Reign', 'Riley', 'Rocco', 'Sage', 'Sam', 'Samantha', 'Santino', 'Selene', 'Sean', 'Sky', 'Sophia', 'Theo', 'Troy', 'Van', 'Vernon', 'Vince', 'Winter', 'Wyatt', 'Yana', 'Yuri', 'Zach', 'Zara', 'Zeke'];
        $lastNames = ['Aguilar', 'Alvarez', 'Anderson', 'Aquino', 'Aragon', 'Bautista', 'Belmont', 'Blackwell', 'Bolton', 'Brooks', 'Calderon', 'Campbell', 'Castillo', 'Cortez', 'Cruz', 'Davenport', 'Delgado', 'Domingo', 'Donovan', 'Durham', 'Edwards', 'Estrada', 'Evans', 'Fernandez', 'Figueroa', 'Fletcher', 'Flores', 'Ford', 'Gaines', 'Garcia', 'Gomez', 'Gonzales', 'Goodwin', 'Grant', 'Greyson', 'Guerrero', 'Hale', 'Hall', 'Hamilton', 'Hernandez', 'Hoffman', 'Howard', 'Ibarra', 'Ingram', 'Jacinto', 'Javier', 'Johnson', 'Juarez', 'Kennedy', 'Knight', 'Larsen', 'Lawson', 'Lim', 'Lopez', 'Luna', 'Madrigal', 'Manalo', 'Marquez', 'Martin', 'Mendez', 'Miller', 'Montgomery', 'Navarro', 'Nelson', 'Nieves', 'Nolasco', 'Ocampo', 'Oliver', 'Ortiz', 'Owens', 'Pacheco', 'Parker', 'Patterson', 'Perez', 'Pineda', 'Reeves', 'Reyes', 'Rodriguez', 'Romero', 'Roxas', 'Salazar', 'Santos', 'Sawyer', 'Scott', 'Silva', 'Simpson', 'Smith', 'Soriano', 'Steele', 'Sullivan', 'Thompson', 'Torres', 'Turner', 'Valdez', 'Vega', 'Velasco', 'Villanueva', 'Ward', 'Watson', 'Williams', 'Wilson', 'Young', 'Zamora', 'Zhang', 'Zimmerman'];

        $lang = rand(1, 15); // assume you have 4 languages
        $categories = ['encyclopedia', 'english', 'novel', 'reference', 'science', 'textbook', 'filipiniana', 'fiction', 'general', 'math'];
        $file_size = ['GB', 'MB'];
        $file_type = ['MP3', 'M4A'];
        $editions = ['1st', '2nd', '3rd', 'Revised', 'Collector’s'];
        $organizations = ['International', 'Global', 'University'];
        $adjectives = ['Lost', 'Burning', 'Hidden', 'Dark', 'Golden', 'Fallen', 'Forgotten', 'Digital', 'Crystal', 'Electric', 'Eternal', 'Silent', 'Broken', 'Shattered', 'Neon', 'Quantum', 'Sacred', 'Violet', 'Iron', 'Phantom', 'Celestial', 'Frozen', 'Mirrored', 'Scarlet', 'Fractal', 'Arcane', 'Gravity', 'Infinite', 'Synthetic', 'Orbital', 'Astral', 'Radiant', 'Wicked', 'Prime', 'Parallel', 'Nocturnal', 'Vortex', 'Encrypted', 'Haunted', 'Decoded', 'Static', 'Ghosted', 'Shadowed', 'Forgotten', 'Emerald', 'Obsidian', 'Solar', 'Lunar', 'Stormborne', 'Silent', 'Ethereal', 'Ancient', 'Boundless', 'Distant', 'Chaotic', 'Harmonic', 'Spectral', 'Cobalt', 'Infernal', 'Mystic', 'Primal', 'Blazing', 'Ivory', 'Onyx', 'Twilight', 'Ambient', 'Hidden', 'Timeless', 'Lucid', 'Arcadian', 'Radiant', 'Bound', 'Tempest', 'Gilded', 'Hallowed', 'Encrypted', 'Encrypted', 'Feral', 'Majestic', 'Infinite', 'Pale', 'Serene', 'Crimson', 'Azure', 'Sapphire', 'Vast', 'Abyssal', 'Binary', 'Encrypted', 'Feral', 'Eclipsed', 'Quantum', 'Everlasting', 'Forged', 'Stormforged', 'Pulsebound', 'Runic', 'Holographic'];
        $nouns = ['Empire', 'Dreams', 'City', 'Chronicles', 'Library', 'Algorithm', 'Moon', 'Echoes', 'Machine', 'Rebellion', 'Kingdoms', 'Horizons', 'Machines', 'Legends', 'Rituals', 'Fragments', 'Signals', 'Shores', 'Hollows', 'Archives', 'Miracles', 'Circuits', 'Paradox', 'Odyssey', 'Spectrum', 'Syndicate', 'Catalysts', 'Prophecy', 'Nebula', 'Voyagers', 'Realm', 'Domain', 'Construct', 'Pulse', 'Network', 'Archive', 'Portal', 'Uprising', 'Dynasty', 'Citadel', 'Voyage', 'Galaxy', 'Storms', 'Stars', 'Sins', 'Artifacts', 'Ruins', 'Horizons', 'Origins', 'Genesis', 'Vaults', 'Lineage', 'Tides', 'Nomads', 'Wanderers', 'Keepers', 'Dreamscape', 'Manuscripts', 'Symphony', 'Equinox', 'Sanctum', 'Legacy', 'Fables', 'Covenant', 'Spectrum', 'Horizons', 'Pillars', 'Threshold', 'Temple', 'Hymn', 'Reflections', 'Mirage', 'Reverie', 'Aeons', 'Cycles', 'Blueprints', 'Constructs', 'Designs', 'Eclipse', 'Reactor', 'Circuitry', 'Codex', 'Monolith', 'Grid', 'Matrix', 'Pulsewave', 'Convergence', 'Voyage', 'Fortress', 'Harbor', 'Frontier', 'Origin', 'Cascade', 'Rift', 'Verse', 'Continuum'];
        $type = ['audio', 'book', 'dissertation', 'electronic', 'newspaper', 'periodical', 'serial', 'thesis', 'vertical'];
        $cities = ['New York, NY',    'Boston, MA',    'Cambridge, MA',    'Oxford, UK',    'London, UK',    'San Francisco, CA',    'Toronto, ON',    'Sydney, AU',    'Singapore',    'Manila, PH'];

        $branch = Branch::all()->pluck('id')->toArray();
        $language = Language::all()->pluck('id')->toArray();
        $publisher = Publisher::all()->pluck('id')->toArray();

        $authors = Author::all()->pluck('id')->toArray();

        // Generate 50 random items
        for ($i = 1; $i <= 15; $i++) {
            $title = $adjectives[array_rand($adjectives)].' '.$nouns[array_rand($nouns)];
            $call_number = rand(100, 999).'.'.chr(rand(65, 90)).str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $year_published = rand(1990, 2023);
            $place_of_publication = $cities[array_rand($cities)];
            $item_type = $type[array_rand($type)];
            $description = 'This is a description for the item titled '.$title.'. It is a fascinating work that explores various themes and ideas relevant to its genre and subject matter.';

            $branch_id = $branch[array_rand($branch)];
            $language_id = $language[array_rand($language)];
            $publisher_id = $publisher[array_rand($publisher)];

            $isbn = '978'.str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);

            $item = Item::create([
                'title' => $title,
                'call_number' => $call_number,
                'year_published' => $year_published,
                'place_of_publication' => $place_of_publication,
                'item_type' => 'book',

                'description' => $description,

                'branch_id' => $branch_id,
                'language_id' => $language_id,
                'publisher_id' => $publisher_id,
            ]);

            ExtendedBibliography::create([
                'book' => [
                    'isbn_issn' => $isbn,
                    'category' => $categories[array_rand($categories)],
                    'edition' => $editions[array_rand($editions)],
                    'pages' => rand(100, 1000),
                ],
            ], $item);

            for ($j = 0; $j < rand(1, 3); $j++) {
                ItemAuthors::create([
                    'item_id' => $item->id,
                    'author_id' => $authors[array_rand($authors)],
                    'role' => 'author',
                ]);
            }

            $lastId = Acquisition::max('id') ?? 0;
            $nextId = $lastId + 1;

            $purchaseId = 'REF'.now()->format('YmdHis').str_pad($nextId, 8, '0', STR_PAD_LEFT);

            $acquisition = Acquisition::create([
                'purchaseId' => $purchaseId,
                'acquisition_mode' => 'purchased',
                'acquisition_date' => now()->subDays(rand(1, 365)),
                'dealer' => 'Sample Book Supplier',
                'remarks' => 'Seeder-generated acquisition',
                'received_by' => Librarian::inRandomOrder()->first()->id,
            ]);

            $copies = rand(1, 5);
            $price = rand(250, 1500);
            $discount = rand(0, 100);

            AcquisitionLine::create([
                'quantity' => $copies,
                'unit_price' => $price,
                'discount' => $discount,
                'net_price' => ($copies * $price) - $discount,
                'acquisition_id' => $acquisition->id,
                'item_id' => $item->id,
            ]);

            $sectionId = Section::where('branch_id', $item->branch_id)
                ->inRandomOrder()
                ->value('id');

            Accessions::create([
                'accession_number' => null,
                'shelf_location' => 'No information yet, try checking the location details.',
                'status' => 'available',
                'remarks' => null,

                'item_id' => $item->id,
                'section_id' => $sectionId,
                'acquisition_id' => $acquisition->id,
            ]);

        }
    }
}
