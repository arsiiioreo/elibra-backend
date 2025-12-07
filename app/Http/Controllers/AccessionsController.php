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
            'book' => ['value' => 'BK'],
            'thesis' => ['value' => 'UT'],
            'dissertation' => ['value' => 'GT'],
            'audio' => ['value' => 'AU'],
            'serial' => ['value' => 'SE'],
            'periodical' => ['value' => 'PE'],
            'electronic' => ['value' => 'EL'],
            'vertical' => ['value' => 'VF'],
            'newspaper' => ['value' => 'NC'],
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

        $prefix = $prefixes[$item->item_type];

        // ✅ Get last number ONCE
        $last = Accessions::where('accession_code', 'like', $prefix['value'].'%')
            ->orderBy('accession_code', 'desc')
            ->first();

        $lastNumber = $last
            ? intval(substr($last->accession_code, strlen($prefix['value'])))
            : 0;

        $modes = ['donated', 'purchased', 'gift', 'exchange'];

        $acquisition = Acquisition::create([
            // 'purchaseId' => '',
            'acquisition_mode' => $modes[rand(0, 3)],
            'dealer' => 'Hindi Ah',
            'acquisition_date' => now(),
            'remarks' => '',
            'received_by' => 1,
        ]);

        for ($i = 0; $i < $copies; $i++) {

            // ✅ Increment PROPERLY
            $lastNumber++;
            $newAccessionNumber = $prefix['value'].str_pad($lastNumber, 7, '0', STR_PAD_LEFT);

            // ✅ Create accession
            Accessions::create([
                'item_id' => $item->id,
                'accession_code' => $newAccessionNumber,
                'shelf_location' => 'Sa kabilang shelf',
                'status' => 'available',
                'section_id' => 1,
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

    public static function create($request, $item, $acquisition)
    {
        $prefixes = [
            'book' => ['value' => 'BK'],
            'thesis' => ['value' => 'UT'],
            'dissertation' => ['value' => 'GT'],
            'audio' => ['value' => 'AU'],
            'serial' => ['value' => 'SE'],
            'periodical' => ['value' => 'PE'],
            'electronic' => ['value' => 'EL'],
            'vertical' => ['value' => 'VF'],
            'newspaper' => ['value' => 'NC'],
        ];

        $prefix = $prefixes[$item->item_type];

        // ✅ Get last number ONCE
        $last = Accessions::where('accession_code', 'like', $prefix['value'].'%')
            ->orderBy('accession_code', 'desc')
            ->first();

        $lastNumber = $last
            ? intval(substr($last->accession_code, strlen($prefix['value'])))
            : 0;

        for ($i = 0; $i < $request->copies; $i++) {

            // ✅ Increment PROPERLY
            $lastNumber++;
            $newAccessionNumber = $prefix['value'].str_pad($lastNumber, 7, '0', STR_PAD_LEFT);

            // ✅ Create accession
            Accessions::create([
                'accession_code' => $newAccessionNumber,
                'shelf_location' => $request->shelf_location ?? 'No information yet, try checking the location details.',
                'status' => 'available',
                // 'date_acquired' => now(),
                'remarks' => $request->accession_remarks,

                'item_id' => $item->id,
                'section_id' => $request->section_id,
                'acquisition_id' => $acquisition->id,
            ]);
        }
    }
}
