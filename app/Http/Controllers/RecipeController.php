<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
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
    public function store(StoreRecipeRequest $request)
    {
        $validated = $request->validated();
        $recipe = Recipe::create($validated);

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

    public function showBySlug(string $slug)
    {
        $recipe = Recipe::where('slug', $slug)->with(['favorites', 'ratings'])->firstOrFail();
        Gate::authorize('view', $recipe);

        return $recipe;
    }

    public function showByCategorySlug(string $categorySlug)
    {
        $recipes = Recipe::whereHas('categories', function ($query) use ($categorySlug) {
            $query->where('slug', $categorySlug);
        })->get();

        return response()->json($recipes);
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
    public function update(UpdateRecipeRequest $request, string $id)
    {
        $validated = $request->validated();
        $recipe = Recipe::findOrFail($id);
        $recipe->update($validated);

        return response()->json($validated, 200);
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
