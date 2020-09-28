<?php

namespace Domains\Links\Observers;

use Domains\Links\Exceptions\UnapprovedLinkLimitReachedException;
use Domains\Links\Models\Link;

class LinkObserver
{
    public function creating(Link $link)
    {
        $pendingCount = $link->newModelQuery()
            ->forAuthorWithEmail($link->author_email)
            ->unapproved()
            ->count();

        throw_if(
            $pendingCount >= config('links.max_unapproved_links'),
            new UnapprovedLinkLimitReachedException()
        );
    }
}
