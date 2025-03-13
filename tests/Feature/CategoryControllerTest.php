<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_list_all_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_a_category(): void
    {
        $data = ['name' => 'Mexican'];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_it_can_show_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $category->name]);
    }

    public function test_it_can_update_a_category(): void
    {
        $category = Category::factory()->create();
        $data = ['name' => 'Updated Category'];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_it_can_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_it_can_search_categories(): void
    {
        Category::factory()->create(['name' => 'Mexican']);
        Category::factory()->create(['name' => 'Italian']);
        Category::factory()->create(['name' => 'Chinese']);

        $response = $this->getJson('/api/categories/search?query=Italian');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Italian'])
            ->assertJsonMissing(['name' => 'Mexican'])
            ->assertJsonMissing(['name' => 'Chinese']);
    }
}
