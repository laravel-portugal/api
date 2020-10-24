<?php

namespace Domains\Accounts\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class RedirectIfAuthenticated
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        if (!$this->auth->guard($guard)->guest()) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}