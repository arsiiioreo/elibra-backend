<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Betsie M. Dela Cruz',
                'sex' => '1',
                'email' => 'betsie.m.dela-cruz@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Merelisa R. Concordia',
                'sex' => '1',
                'email' => "merelisa.r.concordia@isu.edu.ph",
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Aileen J. Lagmay',
                'sex' => '1',
                'email' => "aileen.j.lagmay@isu.edu.ph",
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Juliet D. Villanueva',
                'sex' => '1',
                'email' => "juliet.d.villanueva@isu.edu.ph",
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Hyacinth A. Villanueva',
                'sex' => '1',
                'email' => 'hyacinth.a.villanueva@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Chrisitian L. Fermin',
                'sex' => '0',
                'email' => 'christian.l.fermin@isu.edu.ph',
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Lorna B. Layugan',
                'sex' => '1',
                'email' => "lorna.b.layugan@isu.edu.ph",
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
            [
                'name' => 'Lady B. Malab',
                'sex' => '1',
                'email' => "lady.b.malab@isu.edu.ph",
                'contact_number' => null,
                'password' => Hash::make('isuelibra2025'),
                'role' => '1',
                'pending_registration_approval' => '0',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
