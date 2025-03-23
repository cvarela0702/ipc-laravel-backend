<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_recipes(): void
    {
        Recipe::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())->getJson('/api/recipes');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_a_recipe(): void
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'title' => 'Test Recipe',
            'description' => 'Test Description',
            'ingredients' => 'Test Ingredients',
            'instructions' => 'Test Instructions',
            'image_url' => 'https://example.com/image.jpg',
            'prep_time_hours' => 1,
            'cook_time_hours' => 1,
            'servings' => 4,
            'calories' => 400,
            'prep_time_minutes' => 30,
            'cook_time_minutes' => 30,
            'video_url' => 'https://example.com/video.mp4',
            'slug' => 'test-recipe',
        ];

        $response = $this->actingAs($user)->postJson('/api/recipes', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('recipes', $data);
    }

    public function test_it_can_show_a_recipe(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs(User::factory()->create())->getJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $recipe->title]);
    }

    public function test_user_can_delete_own_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_user_cannot_delete_others_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(403);
    }

    public function test_it_can_search_recipes(): void
    {
        $user = User::factory()->create();
        Recipe::factory()->create(['title' => 'Test Recipe', 'user_id' => $user->id]);
        Recipe::factory()->create(['title' => 'Another Recipe', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/recipes/search?query=test');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Test Recipe'])
            ->assertJsonMissing(['title' => 'Another Recipe']);
    }

    public function test_user_can_update_own_recipe()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->putJson("/api/recipes/{$recipe->id}", ['title' => 'Updated Title'])
            ->assertStatus(200);
    }

    public function test_user_cannot_update_others_recipe()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $this->actingAs($user)
            ->putJson("/api/recipes/{$recipe->id}", ['title' => 'Updated Title'])
            ->assertStatus(403);
    }

    public function test_it_can_show_recipe_by_slug(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs(User::factory()->create())->getJson("/api/recipes/slug/{$recipe->slug}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $recipe->title]);
    }
}
