<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [

            'XI-RPL 1',
            'XI-RPL 2',
            'XI-RPL 3',

        ];

        foreach ($classes as $class) {

            SchoolClass::create([
                'name' => $class
            ]);
        }
    }
}