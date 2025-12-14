<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Author
        $firstNames = ['Adrian', 'Aiden', 'Althea', 'Alexa', 'Amara', 'Andre', 'Angela', 'Arvin', 'Asher', 'Aubrey', 'Bea', 'Benjamin', 'Bianca', 'Bryle', 'Brent', 'Caleb', 'Celine', 'Charles', 'Cheska', 'Cyrus', 'Darian', 'Daniel', 'Danica', 'Darren', 'Dave', 'Denise', 'Devon', 'Dylan', 'EJ', 'Eleanor', 'Elliot', 'Elijah', 'Ella', 'Eloise', 'Enzo', 'Ethan', 'Felix', 'Finn', 'Frances', 'Franco', 'Gabriel', 'Gavin', 'Gwen', 'Hailey', 'Hannah', 'Harold', 'Harvey', 'Hazel', 'Ian', 'Ira', 'Isabella', 'Isaac', 'Ivy', 'Jay', 'Jace', 'Jacob', 'Jana', 'Jared', 'Jasmine', 'Jax', 'Jillian', 'Jiro', 'Jude', 'Kael', 'Kai', 'Kaira', 'Keith', 'Kenji', 'Kevin', 'Kyla', 'Lance', 'Lara', 'Lawrence', 'Lea', 'Liam', 'Luca', 'Lyra', 'Macy', 'Marco', 'Marcus', 'Mariel', 'Mason', 'Matteo', 'Mia', 'Mikhail', 'Miles', 'Nathan', 'Nicole', 'Noah', 'Nova', 'Owen', 'Olivia', 'Parker', 'Phoebe', 'Rafael', 'Raine', 'Reese', 'Reign', 'Riley', 'Rocco', 'Sage', 'Sam', 'Samantha', 'Santino', 'Selene', 'Sean', 'Sky', 'Sophia', 'Theo', 'Troy', 'Van', 'Vernon', 'Vince', 'Winter', 'Wyatt', 'Yana', 'Yuri', 'Zach', 'Zara', 'Zeke'];
        $lastNames = ['Aguilar', 'Alvarez', 'Anderson', 'Aquino', 'Aragon', 'Bautista', 'Belmont', 'Blackwell', 'Bolton', 'Brooks', 'Calderon', 'Campbell', 'Castillo', 'Cortez', 'Cruz', 'Davenport', 'Delgado', 'Domingo', 'Donovan', 'Durham', 'Edwards', 'Estrada', 'Evans', 'Fernandez', 'Figueroa', 'Fletcher', 'Flores', 'Ford', 'Gaines', 'Garcia', 'Gomez', 'Gonzales', 'Goodwin', 'Grant', 'Greyson', 'Guerrero', 'Hale', 'Hall', 'Hamilton', 'Hernandez', 'Hoffman', 'Howard', 'Ibarra', 'Ingram', 'Jacinto', 'Javier', 'Johnson', 'Juarez', 'Kennedy', 'Knight', 'Larsen', 'Lawson', 'Lim', 'Lopez', 'Luna', 'Madrigal', 'Manalo', 'Marquez', 'Martin', 'Mendez', 'Miller', 'Montgomery', 'Navarro', 'Nelson', 'Nieves', 'Nolasco', 'Ocampo', 'Oliver', 'Ortiz', 'Owens', 'Pacheco', 'Parker', 'Patterson', 'Perez', 'Pineda', 'Reeves', 'Reyes', 'Rodriguez', 'Romero', 'Roxas', 'Salazar', 'Santos', 'Sawyer', 'Scott', 'Silva', 'Simpson', 'Smith', 'Soriano', 'Steele', 'Sullivan', 'Thompson', 'Torres', 'Turner', 'Valdez', 'Vega', 'Velasco', 'Villanueva', 'Ward', 'Watson', 'Williams', 'Wilson', 'Young', 'Zamora', 'Zhang', 'Zimmerman'];

        // Seeding Authors for Items
        for ($i = 1; $i <= 20; $i++) {
            Author::create([
                'last_name' => $lastNames[array_rand($lastNames)],
                'first_name' => $firstNames[array_rand($firstNames)],
                'middle_initial' => chr(rand(65, 90)),
            ]);
        }
    }
}
