<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_favorites()
    {
        Favorite::factory()->count(3)->create();

        $response = $this->getJson('/api/favorites');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_a_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ];

        $response = $this->postJson('/api/favorites', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('favorites', $data);
    }

    public function test_it_can_show_a_favorite()
    {
        $favorite = Favorite::factory()->create();

        $response = $this->getJson("/api/favorites/{$favorite->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['user_id' => $favorite->user_id]);
    }

    public function test_it_can_delete_a_favorite()
    {
        $favorite = Favorite::factory()->create();

        $response = $this->deleteJson("/api/favorites/{$favorite->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('favorites', ['id' => $favorite->id]);
    }
}
