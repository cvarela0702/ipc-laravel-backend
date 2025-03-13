<?php
// Question.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Question extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id', 'recipe_id', 'question',
    ];

    public function toSearchableArray()
    {
        return [
            'question' => $this->question,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
