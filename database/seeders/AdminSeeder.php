<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(

            [
                'email' => 'admintaskmanagement@gmail.com'
            ],

            [
                'name' => 'Administrator',

                'password' => Hash::make('RTM@admin123!'),

                'role' => 'admin',

                'is_approved' => true,
            ]

        );
    }
}