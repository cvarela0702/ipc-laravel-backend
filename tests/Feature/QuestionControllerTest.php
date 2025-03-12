<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_questions()
    {
        Question::factory()->count(3)->create();

        $response = $this->getJson('/api/questions');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_a_question()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'question' => 'Test Question',
        ];

        $response = $this->postJson('/api/questions', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('questions', $data);
    }

    public function test_it_can_show_a_question()
    {
        $question = Question::factory()->create();

        $response = $this->getJson("/api/questions/{$question->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['question' => $question->question]);
    }

    public function test_it_can_update_a_question()
    {
        $question = Question::factory()->create();
        $data = ['question' => 'Updated Question'];

        $response = $this->putJson("/api/questions/{$question->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('questions', $data);
    }

    public function test_it_can_delete_a_question()
    {
        $question = Question::factory()->create();

        $response = $this->deleteJson("/api/questions/{$question->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }
}
