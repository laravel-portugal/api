<?php

namespace Domains\Accounts\Middleware;

use Closure;
use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware as ThrottleMiddleware;
use GrahamCampbell\Throttle\Throttle;
use Illuminate\Contracts\Auth\Factory as Auth;

class ThrottleGuestMiddleware extends ThrottleMiddleware
{
    protected Auth $auth;

    public function __construct(Auth $auth, Throttle $throttle)
    {
        parent::__construct($throttle);
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $limit = 10, $time = 60)
    {
        if ($this->auth->guard()->guest()) {
            return parent::handle($request,  $next, $limit, $time);
        }

        return $next($request);
    }
}