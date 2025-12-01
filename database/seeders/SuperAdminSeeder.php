<?php

namespace Database\Seeders;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'last_name' => 'Balico',
            'middle_initial' => null,
            'first_name' => 'Reignromar Chryzel',
            'sex' => 'male',
            'email' => 'reignromarchryzel.balico@isu.edu.ph',
            'contact_number' => '',
            'password' => Hash::make('isuelibra2025'),
            'role' => '0',
            'status' => '0',
            'pending_registration_approval' => '0',
        ]);

        $librarian = User::create([
            'last_name' => 'Dela Cruz',
            'middle_initial' => 'M',
            'first_name' => 'Betsie',
            'sex' => 'female',
            'email' => 'betsie.m.dela-cruz@isu.edu.ph',
            'contact_number' => '',
            'password' => Hash::make('isuelibra2025'),
            'role' => '1',
            'status' => '0',
            'pending_registration_approval' => '0',
        ]);

        Librarian::create([
            'user_id' => $librarian->id,
            'username' => 'betsie',
            'section_id' => 1,
        ]);
    }
}
