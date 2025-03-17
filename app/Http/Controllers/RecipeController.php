<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Recipe::class);
        return Recipe::all();
    }

    public function search(Request $request)
    {
        Gate::authorize('viewAny', Recipe::class);
        $query = $request->get('query');
        $recipes = Recipe::search($query)->get();
        return response()->json($recipes);
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
        Gate::authorize('create', Recipe::class);
        $recipe = Recipe::create($request->all());
        return response()->json($recipe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe = Recipe::findorFail($id);
        Gate::authorize('view', $recipe);
        return $recipe;
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
        $recipe = Recipe::findOrFail($id);
        Gate::authorize('update', $recipe);
        $recipe->update($request->all());
        return response()->json($recipe, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        Gate::authorize('delete', $recipe);
        Recipe::destroy($recipe->id);
        return response()->json(null, 204);
    }
}
