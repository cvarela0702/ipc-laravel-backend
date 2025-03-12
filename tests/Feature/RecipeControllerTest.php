<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_list_all_recipes(): void
    {
        Recipe::factory()->count(3)->create();

        $response = $this->getJson('/api/recipes');

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
        ];

        $response = $this->postJson('/api/recipes', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('recipes', $data);
    }

    public function test_it_can_show_a_recipe(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $recipe->title]);
    }

    public function test_it_can_update_a_recipe(): void
    {
        $recipe = Recipe::factory()->create();
        $data = ['title' => 'Updated Recipe'];

        $response = $this->putJson("/api/recipes/{$recipe->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('recipes', $data);
    }

    public function test_it_can_delete_a_recipe(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }
}
