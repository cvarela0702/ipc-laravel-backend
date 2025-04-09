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

        $response = $this->actingAs($user)->postJson('/api/comments', $data);

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

    public function test_user_cannot_delete_other_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_reply_to_other_user_comments()
    {
        $user = User::factory()->create();
        $parentComment = Comment::factory()->create();
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $parentComment->recipe_id,
            'content' => 'Test Reply',
            'parent_id' => $parentComment->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/comments', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('comments', $data);
    }

    public function test_user_get_error_when_trying_to_reply_to_non_parent_comments()
    {
        $user = User::factory()->create();
        $parent_comment = Comment::factory()->create(['parent_id' => null]);
        $child_comment = Comment::factory()->create(['parent_id' => $parent_comment->id]);
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $child_comment->recipe_id,
            'content' => 'Test Reply',
            'parent_id' => $child_comment->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/comments', $data);

        $response->assertStatus(422);
    }

    /**
     * Test comments count increment on comment creation
     */
    public function test_comments_count_increment_on_comment_creation()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'content' => 'Test Comment',
        ];

        $this->actingAs($user)->postJson('/api/comments', $data);

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'comments_count' => 1,
        ]);
    }
}
