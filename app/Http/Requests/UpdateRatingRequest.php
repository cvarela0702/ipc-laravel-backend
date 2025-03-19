<?php

namespace App\Http\Requests;

use App\Models\Rating;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $rating = Rating::findOrFail($this->route('rating'));
        Gate::authorize('update', $rating);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'stars' => 'required|integer|between:1,5',
        ];
    }
}
