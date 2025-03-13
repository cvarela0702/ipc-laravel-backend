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

Route::get('recipes/search', [RecipeController::class, 'search']);
Route::get('questions/search', [QuestionController::class, 'search']);
Route::get('categories/search', [CategoryController::class, 'search']);

Route::apiResource('recipes', RecipeController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('ratings', RatingController::class);
Route::apiResource('favorites', FavoriteController::class);
Route::apiResource('questions', QuestionController::class);
Route::apiResource('answers', AnswerController::class);
Route::apiResource('categories', CategoryController::class);
