<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DatabaseSeeder::class,
            CategorySeeder::class,
            RecipeSeeder::class,
            FavoritesSeeder::class,
            RatingsSeeder::class,
        ]);
    }
}
