<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AccountsLogoutController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(): Response
    {
        $this->auth::logout();

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
