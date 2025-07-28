<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campus::create([
            'campus' => 'Echague Main Campus',
            'abbrev' => 'ISU-E',
            'address' => 'San Fabian, Echague, Isabela',
        ]);

        User::create([
            'name' => 'Reignromar Chryzel Balico',
            'sex' => '1',
            'email' => 'reignromarchryzel.balico@isu.edu.ph',
            'contact_number' => '09560964897',
            'password' => Hash::make('isuelibra2025'),
            'role' => '0',
            'pending_registration_approval' => '0',
            'campus_id' => 1, 
        ]);

        User::create([
            'name' => 'Eugene G. Tobias',
            'sex' => '1',
            'email' => 'eugene.g.tobias@isu.edu.ph',
            'password' => Hash::make('isuelibra2025'),
            'role' => '0',
            'pending_registration_approval' => '0',
            'campus_id' => 1, 
        ]);

        User::create([
            'name' => 'Princess Jef A. Mamaril',
            'sex' => '0',
            'email' => 'jef.a.mamaril@isu.edu.ph',
            'password' => Hash::make('jefmamaril'),
            'role' => '0',
            'pending_registration_approval' => '0',
            'campus_id' => 1, 
        ]);
    }
}
