<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Comment::class);

        return Comment::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();

        // validate that users cannot reply to replies
        if (isset($validated['parent_id'])) {
            $parentComment = Comment::find($validated['parent_id']);
            if ($parentComment && $parentComment->parent_id) {
                return response()->json(['error' => 'Cannot reply to a reply', 'parent_id' => 'Invalid'], 422);
            }
        }
        $validated['user_id'] = auth()->user()->id;

        $comment = Comment::create($validated);
        $comment->recipe
            ->update(['comments_count' => $comment->recipe->comments_count + 1]);

        if ($comment->parent_id) {
            $parentComment = Comment::find($comment->parent_id);
            $parentComment->update(['replies_count' => $parentComment->replies_count + 1]);
        }

        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::findorFail($id);
        Gate::authorize('view', $comment);

        return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, string $id)
    {
        $validated = $request->validated();
        $comment = Comment::findorFail($id);
        $comment->update($validated);

        return response()->json($comment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findorFail($id);
        Gate::authorize('delete', $comment);
        Comment::destroy($id);
        $commentsCount = ($comment->recipe->comments_count - 1) < 0 ? 0 : ($comment->recipe->comments_count - 1);
        $comment->recipe
            ->update(['comments_count' => $commentsCount]);

        if ($comment->parent_id) {
            $parentComment = Comment::find($comment->parent_id);
            $parentComment->update(['replies_count' => $parentComment->replies_count - 1]);
        }

        return response()->json(null, 204);
    }

    /** show by recipe slug, including pagination for load more functionality in the frontend */
    public function showByRecipeSlug(string $slug, int $page = 1, int $perPage = 10)
    {
        $comments = Comment::whereHas('recipe', function ($query) use ($slug) {
            $query->where('slug', $slug)->where('parent_id', null);
        })->with(['replies', 'users'])->paginate($perPage, ['*'], 'page', $page);

        return response()->json($comments);
    }

    /**
     * show replies for a specific comment
     */
    public function showReplies(string $id, int $page = 1, int $perPage = 10)
    {
        // validate that the comment exists
        $comment = Comment::findorFail($id);
        Gate::authorize('view', $comment);

        $replies = Comment::where('parent_id', $id)->with('users')->paginate($perPage, ['*'], 'page', $page);

        return response()->json($replies);
    }
}
