<?php

namespace Domains\Discussions\Policies;

use Domains\Accounts\Enums\AccountTypeEnum;
use Domains\Accounts\Models\User;
use Domains\Discussions\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Question $question): bool
    {
        return $question->author->is($user);
    }

    public function delete(User $user, Question $question): bool
    {
        return $question->author->is($user) || $user->hasRole(AccountTypeEnum::ADMIN);
    }
}
