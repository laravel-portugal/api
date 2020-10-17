<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AccountsLogoutController extends Controller
{
    public function __invoke(): Response
    {
        auth()->logout();

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
