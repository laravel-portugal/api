<?php

namespace Domains\Links\Exceptions;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class UnapprovedLinkLimitReachedException extends TooManyRequestsHttpException
{
    public function __construct()
    {
        parent::__construct(
            null,
            __('You\'ve reached the max of ' . config('links.max_unapproved_links') . ' unapproved links.'),
        );
    }
}
