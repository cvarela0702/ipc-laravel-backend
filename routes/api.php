<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('recipes/search', [RecipeController::class, 'search'])->middleware('auth:sanctum');
Route::get('questions/search', [QuestionController::class, 'search'])->middleware('auth:sanctum');
Route::get('categories/search', [CategoryController::class, 'search'])->middleware('auth:sanctum');

Route::get('/recipes/slug/{slug}', [RecipeController::class, 'showBySlug'])->middleware('auth:sanctum');
Route::put('/recipes/slug/{slug}', [RecipeController::class, 'updateBySlug'])->middleware('auth:sanctum');
Route::delete('/recipes/slug/{slug}', [RecipeController::class, 'destroyBySlug'])->middleware('auth:sanctum');
Route::get('/recipes/category-slug/{slug}', [RecipeController::class, 'showByCategorySlug'])->middleware('auth:sanctum');

Route::apiResource('recipes', RecipeController::class)->middleware('auth:sanctum');
Route::apiResource('comments', CommentController::class)->middleware('auth:sanctum');
Route::apiResource('ratings', RatingController::class)->middleware('auth:sanctum');
Route::apiResource('favorites', FavoriteController::class)->middleware('auth:sanctum');
Route::apiResource('questions', QuestionController::class)->middleware('auth:sanctum');
Route::apiResource('answers', AnswerController::class)->middleware('auth:sanctum');
Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
