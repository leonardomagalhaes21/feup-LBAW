<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    protected $primaryKey = 'posts_id';

    public $timestamps = false;

    protected $fillable = [
        'posts_id', 
        'title',
    ];

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'questions_id', 'posts_id');
    }

    /**
     * Get the post that owns the question.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'posts_id', 'posts_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'posts_id', 'tags_id');
    }
    public function followers()
    {
        return $this->belongsToMany(User::class, 'users_follow_questions', 'questions_id', 'users_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($question) {
            $question->answers()->each(function ($answer) {
                $answer->delete();
            });
            $question->comments()->each(function ($comment) {
                $comment->delete();
            });
        });
    }
}

