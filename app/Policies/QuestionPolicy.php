<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;



class QuestionPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return Auth::check();
    }

    public function update(User $user, Question $question)
    {
        return $user->id === $question->post->users_id;
    }

    public function delete(User $user, Question $question)
    {
        return $user->id === $question->post->users_id || $user->hasRole('admin') || $user->hasRole('moderator');
    }
}