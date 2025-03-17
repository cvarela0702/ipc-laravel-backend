<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_comments()
    {
        Comment::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())->getJson('/api/comments');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_a_comment()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'content' => 'Test Comment',
        ];

        $response = $this->actingAs(User::factory()->create())->postJson('/api/comments', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('comments', $data);
    }

    public function test_it_can_show_a_comment()
    {
        $comment = Comment::factory()->create();

        $response = $this->actingAs(User::factory()->create())->getJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => $comment->content]);
    }

    public function test_user_can_update_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);
        $data = ['content' => 'Updated Comment'];

        $response = $this->actingAs($user)->putJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('comments', $data);
    }

    public function test_user_cannot_update_other_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        $data = ['content' => 'Updated Comment'];

        $response = $this->actingAs($user)->putJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_delete_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403);
    }
}
