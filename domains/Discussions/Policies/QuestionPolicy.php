<?php

namespace Domains\Discussions\Policies;

use Domains\Accounts\Models\User;
use Domains\Discussions\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Question $question)
    {
        return $question->author->is($user);
    }
}
