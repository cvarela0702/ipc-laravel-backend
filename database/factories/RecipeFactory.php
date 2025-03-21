<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'ingredients' => $this->faker->paragraph,
            'instructions' => $this->faker->paragraph,
            'image_url' => $this->faker->imageUrl(),
            'prep_time_hours' => $this->faker->randomDigit,
            'cook_time_hours' => $this->faker->randomDigit,
            'servings' => $this->faker->randomDigit,
            'calories' => $this->faker->randomDigit,
            'prep_time_minutes' => $this->faker->randomDigit,
            'cook_time_minutes' => $this->faker->randomDigit,
            'video_url' => $this->faker->url,
            'slug' => $this->faker->slug,
        ];
    }
}
