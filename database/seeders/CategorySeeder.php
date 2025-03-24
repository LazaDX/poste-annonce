<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'id' => 1,
            'name' => 'Voitures'
        ]);
        Category::create([
            'id' => 2,
            'name' => 'Fermes & Vergers'
        ]);
        Category::create([
            'id' => 3,
            'name' => 'Appartements meublés'
        ]);
        Category::create([
            'id' => 4,
            'name' => 'Terrains agricoles'
        ]);
        Category::create([
            'id' => 5,
            'name' => 'Chambres'
        ]);
        Category::create([
            'id' => 6,
            'name' => 'Maisons de vacances'
        ]);
        Category::create([
            'id' => 7,
            'name' => 'Bureaux & Commerces'
        ]);
        Category::create([
            'id' => 8,
            'name' => '"Immeubles'
        ]);
        Category::create([
            'id' => 9,
            'name' => 'Appartements'
        ]);
        Category::create([
            'id' => 10,
            'name' => 'Terrains'
        ]);
        Category::create([
            'id' => 11,
            'name' => 'Villas'
        ]);
    }
}











