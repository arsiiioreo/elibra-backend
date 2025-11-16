<?php

namespace Database\Seeders;

use App\Models\Patron;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'last_name' => 'User',
                'middle_initial' => 'D',
                'first_name' => 'Test',
                'sex' => 'male',
                'contact_number' => '09123456789',
                'email' => 'testuser',
                'password' => 'isuelibra2025',
                'role' => '2',
                'pending_registration_approval' => '1',

                'id_number' => 220000,
                'ebc' => '',
                'program_id' => 1,
                'patron_type_id' => '2',
                'address' => 'Alicia, Isabela',
            ],
        ];

        for ($i= 0; $i < 50; $i++) { 
            foreach ($users as $user) {
                $u = User::create([
                    'last_name' => $user['last_name'],
                    'middle_initial' => $user['middle_initial'],
                    'first_name' => $user['first_name'] . $i,
                    'sex' => $user['sex'],
                    'contact_number' => $user['contact_number'],
                    'email' => $user['email'] . '@isu.edu.ph',
                    'password' => $user['password'],
                    'role' => $user['role'],
                    'pending_registration_approval' => $user['pending_registration_approval'],
                ]);
    
                $u->update([
                    'first_name' => $user['first_name'].$u->id,
                    'email' => $user['email'].$u->id.'@isu.edu.ph',
                ]);
    
                Patron::create([
                    'user_id' => $u->id,
                    'id_number' => $user['id_number'] + $u->id,
                    'program_id' => $user['program_id'],
                    'patron_type_id' => $user['patron_type_id'],
                    'address' => $user['address'],
                ]);
            }
        }

    }
}
