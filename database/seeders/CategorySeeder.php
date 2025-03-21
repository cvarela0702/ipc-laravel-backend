<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Mexican', 'description' => 'Traditional Mexican cuisine'],
            ['name' => 'Italian', 'description' => 'Authentic Italian dishes'],
            ['name' => 'Vegan', 'description' => 'Plant-based recipes'],
            ['name' => 'Low Carb', 'description' => 'Low carbohydrate meals'],
            ['name' => 'Desserts', 'description' => 'Sweet treats and desserts'],
            ['name' => 'Seafood', 'description' => 'Delicious seafood recipes'],
            ['name' => 'BBQ', 'description' => 'Barbecue and grilling recipes'],
            ['name' => 'Breakfast', 'description' => 'Breakfast and brunch ideas'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => 'http://localhost:3000/images/categories/' . Str::slug($category['name']) . '.svg',
            ]);
        }
    }
}
