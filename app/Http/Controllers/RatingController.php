<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Rating::class);
        return Rating::all();
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
    public function store(Request $request)
    {
        Gate::authorize('create', Rating::class);
        $rating = Rating::create($request->all());
        return response()->json($rating, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rating = Rating::findorFail($id);
        Gate::authorize('view', $rating);
        return $rating;
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
    public function update(Request $request, string $id)
    {
        $rating = Rating::findorFail($id);
        Gate::authorize('update', $rating);
        $rating->update($request->all());
        return response()->json($rating, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rating = Rating::findorFail($id);
        Gate::authorize('delete', $rating);
        Rating::destroy($id);
        return response()->json(null, 204);
    }
}
