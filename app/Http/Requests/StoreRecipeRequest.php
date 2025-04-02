<?php

namespace App\Http\Requests;

use App\Models\Recipe;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        Gate::authorize('create', Recipe::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'user_id' => 'required|exists:users,id',
            // 'category_ids' => 'required|array|exists:categories,id',
            'image_url' => 'required|string',
            'slug' => 'required|string|unique:recipes,slug',
            'servings' => 'required|integer',
            'prep_time_hours' => 'required|integer',
            'prep_time_minutes' => 'required|integer',
            'cook_time_hours' => 'required|integer',
            'cook_time_minutes' => 'required|integer',
            'video_url' => 'sometimes|string',
            'calories' => 'required|integer',
            'categories' => 'sometimes|array',
        ];
    }
}
