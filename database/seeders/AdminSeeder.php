<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{

    public function run(): void
    {
        Admin::create([
            'first_name' => 'Lazanirina',
            'last_name' => 'Aintsoa',
            'email' => 'lazanirina2800@gmail.com',
            'password' => Hash::make('adminn'),
        ]);
    }
}
