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
        for ($i = 0; $i < 3000; $i++) {
            $firstName = fake()->firstName();
            $middleInitial = strtoupper(Str::random(1));
            $lastName = fake()->lastName();

            $fullName = "$firstName $middleInitial. $lastName";

            User::create([
                'name' => $fullName,
                'sex' => (string) rand(0, 1),
                'email' => strtolower("$firstName.$middleInitial.$lastName") . '@isu.edu.ph',
                'password' => Hash::make('testuser'),
                'role' => (string) rand(1, 2),
                'pending_registration_approval' => (string) rand(0,1),
                'campus_id' => (string) rand(1, 6),
            ]);
        }
    }
}
