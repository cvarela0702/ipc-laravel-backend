<?php

namespace App\Http\Requests;

use App\Models\Recipe;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PutRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $recipe = Recipe::where('slug', $this->route('slug'))->firstOrFail();
        Gate::authorize('update', $recipe);

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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'ingredients' => 'sometimes|string',
            'instructions' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            // 'category_ids' => 'required|array|exists:categories,id',
            'image_url' => 'sometimes|string',
            // 'slug' => 'sometimes|string|unique:recipes,slug',
            'servings' => 'sometimes|integer',
            'prep_time_hours' => 'sometimes|integer',
            'prep_time_minutes' => 'sometimes|integer',
            'cook_time_hours' => 'sometimes|integer',
            'cook_time_minutes' => 'sometimes|integer',
            'video_url' => 'sometimes|string',
            'calories' => 'sometimes|integer',
            'categories' => 'sometimes|array',
        ];
    }
}
