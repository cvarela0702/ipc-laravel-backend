<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class FavoritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $favoriteRecords = [
            ['user_id' => 1, 'recipe_id' => 1],
            ['user_id' => 1, 'recipe_id' => 2],
            ['user_id' => 1, 'recipe_id' => 3],
            ['user_id' => 2, 'recipe_id' => 4],
            ['user_id' => 2, 'recipe_id' => 5],
            ['user_id' => 3, 'recipe_id' => 6],
            ['user_id' => 3, 'recipe_id' => 7],
        ];

        foreach ($favoriteRecords as $record) {
            Favorite::create($record);
            Recipe::find($record['recipe_id'])->increment('favorites_count');
        }
    }
}
