<?php

namespace App\Http\Controllers;

use App\Models\Accessions;
use App\Models\Acquisition;
use App\Models\Author;
use App\Models\ItemAuthors;

class AccessionsController extends Controller
{
    public static function accession($item = null, $copies = 0)
    {
        $prefixes = [
            1 => 'BK',
            2 => 'UT',
            3 => 'GT',
            4 => 'AU',
            5 => 'SE',
            6 => 'PE',
            7 => 'EL',
            8 => 'VF',
            9 => 'NC',
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

        $prefix = $prefixes[$item->item_type_id];

        // ✅ Get last number ONCE
        $last = Accessions::where('accession_number', 'like', $prefix.'%')
            ->orderBy('accession_number', 'desc')
            ->first();

        $lastNumber = $last
            ? intval(substr($last->accession_number, strlen($prefix)))
            : 0;

        $modes = ['donated', 'purchased', 'gift', 'exchange'];

        for ($i = 0; $i < $copies; $i++) {

            // ✅ Increment PROPERLY
            $lastNumber++;
            $newAccessionNumber = $prefix.str_pad($lastNumber, 7, '0', STR_PAD_LEFT);

            // ✅ Acquisition
            $mode = $modes[array_rand($modes)];

            $acquisition = Acquisition::create([
                'purchase_order' => '',
                'dealer' => 'Hindi Ah',
                'acquisition_mode' => $mode,
                'acquisition_date' => now(),
                'price' => $mode == 'purchased' ? rand(150, 10000) : 0,
                'remarks' => '',
            ]);

            // ✅ Create accession
            Accessions::create([
                'item_id' => $item->id,
                'accession_number' => $newAccessionNumber,
                'shelf_location' => 'Sa kabilang shelf',
                'status' => 'available',
                'branch_id' => 1,
                'date_acquired' => now(),
                'acquisition_id' => $acquisition->id,
                'remarks' => null,
            ]);
            
            $author = Author::create([
                'name' => $firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)],
            ]);
    
            ItemAuthors::create([
                'item_id' => $item->id,
                'author_id' => $author->id,
            ]);
        }
    }
}
