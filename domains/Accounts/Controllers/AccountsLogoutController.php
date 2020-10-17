<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AccountsLogoutController extends Controller
{
    public function __invoke(): Response
    {
        Auth::logout();

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
