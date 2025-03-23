<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Models\Favorite;
use Illuminate\Support\Facades\Gate;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Favorite::class);

        return Favorite::all();
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
    public function store(StoreFavoriteRequest $request)
    {
        $validated = $request->validated();
        $favorite = Favorite::create($validated);
        $favorite->recipe->update([
            'favorites_count' => $favorite->recipe->favorites_count + 1,
        ]);

        return response()->json($favorite, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $favorite = Favorite::findorFail($id);
        Gate::authorize('view', $favorite);

        return $favorite;
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
    public function update(UpdateFavoriteRequest $request, string $id)
    {
        //        $validated = $request->validated();
        //        $favorite = Favorite::findorFail($id);
        //        $favorite->update($validated);
        //        return response()->json($favorite, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $favorite = Favorite::findorFail($id);
        Gate::authorize('delete', $favorite);
        $favorite->recipe->update([
            'favorites_count' => $favorite->recipe->favorites_count - 1,
        ]);
        Favorite::destroy($id);

        return response()->json(null, 204);
    }
}
