<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Question::class);
        return Question::all();
    }

    public function search(Request $request)
    {
        Gate::authorize('viewAny', Question::class);
        $query = $request->get('query');
        $questions = Question::search($query)->get();
        return response()->json($questions);
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
    public function store(StoreQuestionRequest $request)
    {
        $validated = $request->validated();
        $question = Question::create($validated);
        return response()->json($question, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = Question::findorFail($id);
        Gate::authorize('view', $question);
        return $question;
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
    public function update(UpdateQuestionRequest $request, string $id)
    {
        $validated = $request->validated();
        $question = Question::findorFail($id);
        $question->update($validated);
        return response()->json($question, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findorFail($id);
        Gate::authorize('delete', $question);
        Question::destroy($id);
        return response()->json(null, 204);
    }
}
