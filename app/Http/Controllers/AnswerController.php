<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Answer::class);
        return Answer::all();
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
    public function store(StoreAnswerRequest $request)
    {
        $validated = $request->validated();
        $answer = Answer::create($validated);
        return response()->json($answer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $answer = Answer::findorFail($id);
        Gate::authorize('view', $answer);
        return $answer;
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
    public function update(UpdateAnswerRequest $request, string $id)
    {
        $validated = $request->validated();
        $answer = Answer::findorFail($id);
        Gate::authorize('update', $answer);
        $answer->update($validated);
        return response()->json($answer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $answer = Answer::findorFail($id);
        Gate::authorize('delete', $answer);
        Answer::destroy($answer->id);
        return response()->json(null, 204);
    }
}
