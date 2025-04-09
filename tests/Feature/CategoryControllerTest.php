<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_list_all_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_regular_user_cannot_create_a_category(): void
    {
        $data = ['name' => 'Mexican'];

        $response = $this->actingAs(User::factory()->create())->postJson('/api/categories', $data);

        $response->assertStatus(403);
    }

    public function test_it_can_show_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs(User::factory()->create())->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $category->name]);
    }

    public function test_regular_user_cannot_update_a_category(): void
    {
        $category = Category::factory()->create();
        $data = ['name' => 'Updated Category'];

        $response = $this->actingAs(User::factory()->create())->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs(User::factory()->create())->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(403);
    }
}
