<?php

namespace Domains\Links\Policies;

use Domains\Accounts\Enums\AccountTypeEnum;
use Domains\Accounts\Models\User;
use Domains\Links\Models\Link;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    public function create(?User $user, string $authorEmail)
    {
        if ($user && ($user->isTrusted() || $user->hasRole(AccountTypeEnum::EDITOR))) {
            return true;
        }

        $pendingCount = Link::forAuthorWithEmail($authorEmail)
            ->unapproved()
            ->count();

        return $pendingCount < config('links.max_unapproved_links');
    }
}
