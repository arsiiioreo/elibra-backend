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
        User::create([
            'last_name' => 'Balico',
            'middle_initial' => null,
            'first_name' => 'Reign Romarchryzel',
            'sex' => 'male',
            'email' => 'reignromarchryzel.balico@isu.edu.ph',
            'contact_number' => '',
            'password' => Hash::make('isuelibra2025'),
            'role' => '0',
            'pending_registration_approval' => '0',
        ]);
    }
}
