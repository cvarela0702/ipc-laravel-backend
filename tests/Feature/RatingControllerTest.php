<?php

namespace Tests\Feature;

use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_ratings()
    {
        Rating::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())->getJson('/api/ratings');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_a_rating()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'stars' => 5,
        ];

        $response = $this->actingAs($user)->postJson('/api/ratings', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('ratings', $data);
    }

    public function test_it_can_show_a_rating()
    {
        $rating = Rating::factory()->create();

        $response = $this->actingAs(User::factory()->create())->getJson("/api/ratings/{$rating->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['stars' => $rating->stars]);
    }

    public function test_user_can_update_own_rating()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create(['user_id' => $user->id]);
        $data = ['stars' => 4];

        $response = $this->actingAs($user)->putJson("/api/ratings/{$rating->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('ratings', $data);
    }

    public function test_user_cannot_update_other_rating()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create();
        $data = ['stars' => 4];

        $response = $this->actingAs($user)->putJson("/api/ratings/{$rating->id}", $data);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_rating()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/ratings/{$rating->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('ratings', ['id' => $rating->id]);
    }

    public function test_user_cannot_delete_other_rating()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/ratings/{$rating->id}");

        $response->assertStatus(403);
    }
}
