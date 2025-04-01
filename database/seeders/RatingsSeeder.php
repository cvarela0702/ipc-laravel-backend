<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ratings = [
            ['user_id' => 1, 'recipe_id' => 1, 'stars' => 5],
            ['user_id' => 2, 'recipe_id' => 1, 'stars' => 4],
            ['user_id' => 3, 'recipe_id' => 2, 'stars' => 3],
            ['user_id' => 4, 'recipe_id' => 3, 'stars' => 5],
            ['user_id' => 5, 'recipe_id' => 4, 'stars' => 2],
            ['user_id' => 6, 'recipe_id' => 5, 'stars' => 4],
            ['user_id' => 7, 'recipe_id' => 6, 'stars' => 5],
        ];

        foreach ($ratings as $rating) {
            Rating::create($rating);
            $recipe = Recipe::find($rating['recipe_id']);

            $recipe->increment('ratings_count');
            $recipe->increment('ratings_sum', $rating['stars']);
            // calculate the average rating
            $recipe->ratings_avg = $recipe->ratings_sum / $recipe->ratings_count;
            $recipe->save();
        }
    }
}
