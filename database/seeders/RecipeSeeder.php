<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'title' => 'Spaghetti Carbonara',
                'description' => 'A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper.',
                'ingredients' => 'Spaghetti, eggs, cheese, pancetta, pepper',
                'instructions' => 'Cook spaghetti. Mix eggs and cheese. Cook pancetta. Combine all with pepper.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 20,
                'calories' => 500,
                'category_ids' => [2],
            ],
            [
                'title' => 'Chicken Tikka Masala',
                'description' => 'A popular Indian dish made with marinated chicken in a spiced curry sauce.',
                'ingredients' => 'Chicken, yogurt, spices, tomatoes, cream',
                'instructions' => 'Marinate chicken. Cook with spices and tomatoes. Add cream.',
                'servings' => 4,
                'prep_time_hours' => 1,
                'prep_time_minutes' => 0,
                'cook_time_hours' => 1,
                'cook_time_minutes' => 0,
                'calories' => 600,
                'category_ids' => [4],
            ],
            [
                'title' => 'Beef Stroganoff',
                'description' => 'A Russian dish made with beef in a sour cream sauce.',
                'ingredients' => 'Beef, mushrooms, onions, sour cream',
                'instructions' => 'Cook beef. Cook mushrooms and onions. Add sour cream.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 30,
                'calories' => 700,
                'category_ids' => [4],
            ],
            [
                'title' => 'Chicken Alfredo',
                'description' => 'An Italian pasta dish made with chicken in a creamy Alfredo sauce.',
                'ingredients' => 'Chicken, pasta, cream, cheese',
                'instructions' => 'Cook pasta. Cook chicken. Add cream and cheese.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 20,
                'calories' => 800,
                'category_ids' => [2],
            ],
            [
                'title' => 'Beef Wellington',
                'description' => 'A British dish made with beef fillet coated with pâté and duxelles, then wrapped in puff pastry.',
                'ingredients' => 'Beef fillet, pâté, mushrooms, puff pastry',
                'instructions' => 'Sear beef. Coat with pâté and duxelles. Wrap in puff pastry.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 30,
                'cook_time_hours' => 1,
                'cook_time_minutes' => 0,
                'calories' => 900,
                'category_ids' => [4],
            ],
            [
                'title' => 'Chicken Parmesan',
                'description' => 'An Italian-American dish made with breaded chicken topped with marinara sauce and cheese.',
                'ingredients' => 'Chicken, bread crumbs, marinara sauce, cheese',
                'instructions' => 'Bread chicken. Cook. Top with marinara sauce and cheese.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 30,
                'calories' => 1000,
                'category_ids' => [2],
            ],
            [
                'title' => 'Lasagna',
                'description' => 'An Italian pasta dish made with layers of pasta, meat, cheese, and tomato sauce.',
                'ingredients' => 'Pasta, meat, cheese, tomato sauce',
                'instructions' => 'Cook pasta. Cook meat. Layer with cheese and tomato sauce.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 30,
                'cook_time_hours' => 1,
                'cook_time_minutes' => 0,
                'calories' => 1100,
                'category_ids' => [2],
            ],
            [
                'title' => 'Chicken Curry',
                'description' => 'An Indian dish made with chicken in a spiced curry sauce.',
                'ingredients' => 'Chicken, spices, tomatoes, onions',
                'instructions' => 'Cook chicken. Cook spices, tomatoes, and onions. Combine.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 30,
                'calories' => 1200,
                'category_ids' => [4],
            ],
            [
                'title' => 'Chicken Noodle Soup',
                'description' => 'A classic soup made with chicken, vegetables, and noodles.',
                'ingredients' => 'Chicken, vegetables, noodles',
                'instructions' => 'Cook chicken. Cook vegetables and noodles. Combine.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 30,
                'calories' => 1300,
                'category_ids' => [4],
            ],
            [
                'title' => 'Chicken Fajitas',
                'description' => 'A Mexican dish made with chicken, peppers, onions, and spices.',
                'ingredients' => 'Chicken, peppers, onions, spices',
                'instructions' => 'Cook chicken. Cook peppers and onions. Combine with spices.',
                'servings' => 4,
                'prep_time_hours' => 0,
                'prep_time_minutes' => 15,
                'cook_time_hours' => 0,
                'cook_time_minutes' => 20,
                'calories' => 1400,
                'category_ids' => [1],
            ],
        ];

        foreach ($recipes as $recipe) {
            $slug = Str::slug($recipe['title']);
            Recipe::create([
                'user_id' => User::factory()->create()->id,
                'title' => $recipe['title'],
                'description' => $recipe['description'],
                'ingredients' => $recipe['ingredients'],
                'instructions' => $recipe['instructions'],
                'image_url' => "http://localhost:3000/images/recipes/{$slug}.jpeg",
                'video_url' => "http://localhost:3000/videos/recipes/{$slug}.mp4",
                'slug' => $slug,
                'servings' => $recipe['servings'],
                'prep_time_hours' => $recipe['prep_time_hours'],
                'prep_time_minutes' => $recipe['prep_time_minutes'],
                'cook_time_hours' => $recipe['cook_time_hours'],
                'cook_time_minutes' => $recipe['cook_time_minutes'],
                'calories' => $recipe['calories'],
            ])->categories()->attach($recipe['category_ids']);
        }
    }
}
