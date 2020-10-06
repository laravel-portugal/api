<?php

namespace Domains\Accounts\Policies;

use Domains\Accounts\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Crypt;

class UserPolicy
{
    use HandlesAuthorization;

    public function verify(?User $user , string $hash)
    {
        return Crypt::decrypt($hash) === $user->email;
    }
}
