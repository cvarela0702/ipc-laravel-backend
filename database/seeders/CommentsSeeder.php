<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            ['user_id' => 1, 'recipe_id' => 1, 'content' => 'This recipe is amazing!'],
            ['user_id' => 2, 'recipe_id' => 1, 'content' => 'I love this recipe!'],
            ['user_id' => 3, 'recipe_id' => 2, 'content' => 'This recipe is okay.'],
            ['user_id' => 4, 'recipe_id' => 3, 'content' => 'I did not like this recipe.'],
            ['user_id' => 5, 'recipe_id' => 4, 'content' => 'This recipe is great!'],
            ['user_id' => 6, 'recipe_id' => 5, 'content' => 'This recipe is fantastic!'],
            ['user_id' => 7, 'recipe_id' => 6, 'content' => 'This recipe is terrible!'],
        ];

        $createdComments = [];

        foreach ($comments as $comment) {
            $createdComments[] = Comment::create($comment);
            Recipe::find($comment['recipe_id'])->increment('comments_count');
        }

        $replyComments = [
            ['user_id' => 5, 'recipe_id' => 1, 'content' => 'I agree!'],
            ['user_id' => 6, 'recipe_id' => 1, 'content' => 'Me too!'],
            ['user_id' => 7, 'recipe_id' => 2, 'content' => 'I think it could be better.'],
            ['user_id' => 1, 'recipe_id' => 3, 'content' => 'I think it is great!'],
            ['user_id' => 2, 'recipe_id' => 4, 'content' => 'I love this recipe!'],
            ['user_id' => 3, 'recipe_id' => 5, 'content' => 'This recipe is fantastic!'],
            ['user_id' => 4, 'recipe_id' => 6, 'content' => 'This recipe is terrible!'],
        ];

        foreach ($createdComments as $comment) {
            foreach ($replyComments as $reply) {
                if ($reply['recipe_id'] === $comment->recipe_id) {
                    Comment::create([
                        'user_id' => $reply['user_id'],
                        'recipe_id' => $reply['recipe_id'],
                        'content' => $reply['content'],
                        'parent_id' => $comment->id,
                    ]);
                    Recipe::find($reply['recipe_id'])->increment('comments_count');
                    // increment the replies for comments
                    $comment->increment('replies_count');
                }
            }
        }
    }
}
