<?php


namespace Domains\Links\Exceptions;


use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class UnapprovedLinkLimitException extends TooManyRequestsHttpException
{
    private string $messageText;

    public function __construct($retryAfter = null, string $message = null, \Throwable $previous = null, ?int $code = 0, array $headers = [])
    {
        parent::__construct(
            null,
            __('You\'ve reached the max of ' . config('links.max_unapproved_links') . ' unapproved links.'),
            null,
            null
        );
    }
}
