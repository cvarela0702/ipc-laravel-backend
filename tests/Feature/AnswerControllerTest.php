<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_answers()
    {
        Answer::factory()->count(3)->create();

        $response = $this->getJson('/api/answers');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_it_can_create_an_answer()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $data = [
            'user_id' => $user->id,
            'question_id' => $question->id,
            'answer' => 'Test Answer',
        ];

        $response = $this->postJson('/api/answers', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('answers', $data);
    }

    public function test_it_can_show_an_answer()
    {
        $answer = Answer::factory()->create();

        $response = $this->getJson("/api/answers/{$answer->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['answer' => $answer->answer]);
    }

    public function test_it_can_update_an_answer()
    {
        $answer = Answer::factory()->create();
        $data = ['answer' => 'Updated Answer'];

        $response = $this->putJson("/api/answers/{$answer->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('answers', $data);
    }

    public function test_it_can_delete_an_answer()
    {
        $answer = Answer::factory()->create();

        $response = $this->deleteJson("/api/answers/{$answer->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('answers', ['id' => $answer->id]);
    }
}
