<?php

// Recipe.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Recipe extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'ingredients',
        'instructions',
        'image_url',
        'prep_time_hours',
        'cook_time_hours',
        'servings',
        'calories',
        'prep_time_minutes',
        'cook_time_minutes',
        'video_url',
        'slug',
        'ratings_count',
        'ratings_sum',
        'ratings_avg',
        'favorites_count',
    ];

    public function toSearchableArray()
    {

        // Ensure the categories relationship is loaded
        if (! $this->relationLoaded('categories')) {
            $this->load('categories');
        }

        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'instructions' => $this->instructions,
            'image_url' => $this->image_url,
            'preparation_time' => $this->prep_time_hours.':'.$this->prep_time_minutes,
            'cooking_time' => $this->cook_time_hours.':'.$this->cook_time_minutes,
            'favorites_count' => $this->favorites_count,
            'ratings_count' => $this->ratings_count,
            'comments_count' => $this->comments_count,
            'ratings_avg' => $this->ratings_avg,
            'servings' => $this->servings,
            'calories' => $this->calories,
            'created_at' => $this->created_at->timestamp,
            'categories' => $this->categories->pluck('name')->toArray(),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('parent_id', null)->limit(10);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class)->where('user_id', auth()->id());
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class)->where('user_id', auth()->id());
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_recipe', 'recipe_id', 'category_id');
    }
}
