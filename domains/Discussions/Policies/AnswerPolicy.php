<?php


namespace Domains\Discussions\Policies;


use Domains\Accounts\Models\User;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Answer $answer): bool
    {
        return $answer->author->is($user);
    }
}
