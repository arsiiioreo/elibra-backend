<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'last_name' => 'Dela Cruz',
                'middle_initial' => 'M',
                'first_name' => 'Betsie',
                'sex' => 'female',
                'email' => 'betsie.m.dela-cruz@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '0'
            ],
            [
                'last_name' => 'Concordia',
                'middle_initial' => 'R',
                'first_name' => 'Merelisa',
                'sex' => 'female',
                'email' => 'merelisa.r.concordia@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '0'
            ],
            [
                'last_name' => 'Lagmay',
                'middle_initial' => 'J',
                'first_name' => 'Aileen',
                'sex' => 'female',
                'email' => 'aileen.j.lagmay@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '0'
            ],
            [
                'last_name' => 'Villanueva',
                'middle_initial' => 'D',
                'first_name' => 'Juliet',
                'sex' => 'female',
                'email' => 'juliet.d.villanueva@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '1'
            ],
            [
                'last_name' => 'Villanueva',
                'middle_initial' => 'A',
                'first_name' => 'Hyacinth',
                'sex' => 'female',
                'email' => 'hyacinth.a.villanueva@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '1'
            ],
            [
                'last_name' => 'Fermin',
                'middle_initial' => 'L',
                'first_name' => 'Christian',
                'sex' => 'male',
                'email' => 'christian.l.fermin@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '1'
            ],
            [
                'last_name' => 'Layugan',
                'middle_initial' => 'B',
                'first_name' => 'Lorna',
                'sex' => 'female',
                'email' => 'lorna.b.layugan@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '1'
            ],
            [
                'last_name' => 'Malab',
                'middle_initial' => 'B',
                'first_name' => 'Lady',
                'sex' => 'female',
                'email' => 'lady.b.malab@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
                'status' => '2'
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
