<?php

namespace Domains\Links\Observers;

use Domains\Accounts\Enums\AccountTypeEnum;
use Domains\Links\Exceptions\UnapprovedLinkLimitReachedException;
use Domains\Links\Models\Link;
use Illuminate\Support\Facades\Auth;

class LinkObserver
{
    public function creating(Link $link)
    {
        $pendingCount = $link->newModelQuery()
            ->forAuthorWithEmail($link->author_email)
            ->unapproved()
            ->count();

        throw_unless(
            Auth::user()->hasRole(AccountTypeEnum::EDITOR)
            || Auth::user()->isTrusted()
            || $pendingCount < config('links.max_unapproved_links'),
            new UnapprovedLinkLimitReachedException()
        );
    }
}
