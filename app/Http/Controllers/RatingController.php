<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Rating;
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
    public function store(StoreRatingRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->user()->id;
        $rating = Rating::create($validated);
        $rating->recipe
            ->update(['ratings_count' => $rating->recipe->ratings_count + 1,
                'ratings_sum' => $rating->recipe->ratings_sum + $rating->stars,
                'ratings_avg' => ($rating->recipe->ratings_sum + $rating->stars) / ($rating->recipe->ratings_count + 1)]);

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
    public function update(UpdateRatingRequest $request, string $id)
    {
        $validated = $request->validated();
        $rating = Rating::findorFail($id);
        $ratingSum = ($rating->recipe->ratings_sum - $rating->stars + $validated['stars']) > 0 ? ($rating->recipe->ratings_sum - $rating->stars + $validated['stars']) : 0;
        $ratingAvg = $rating->recipe->ratings_count !== 0 ? $ratingSum / $rating->recipe->ratings_count : 0;
        $rating->update($validated);
        $rating->recipe->update([
            'ratings_sum' => $ratingSum,
            'ratings_avg' => $ratingAvg,
        ]);

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
        $ratingsAvg = ($rating->recipe->ratings_count - 1) === 0 ? 0 : ($rating->recipe->ratings_sum - $rating->stars) / ($rating->recipe->ratings_count - 1);
        $ratingsCount = ($rating->recipe->ratings_count - 1) < 0 ? 0 : $rating->recipe->ratings_count - 1;
        $ratingsSum = ($rating->recipe->ratings_sum - $rating->stars) < 0 ? 0 : $rating->recipe->ratings_sum - $rating->stars;
        $rating->recipe->update([
            'ratings_count' => $ratingsCount,
            'ratings_sum' => $ratingsSum,
            'ratings_avg' => $ratingsAvg,
        ]);

        return response()->json(null, 204);
    }
}
