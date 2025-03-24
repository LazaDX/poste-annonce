<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Fandresena',
            'last_name' => 'Ismael',
            'email' => 'ismael@gmail.com',
            'contact' => '0345523545',
            'localisation' => 'Antsirabe',
            'password' => Hash::make('123465'),
        ]);
    }
}

