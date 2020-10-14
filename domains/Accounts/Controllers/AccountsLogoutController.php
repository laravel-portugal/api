<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountsLogoutController extends Controller
{
    public function __invoke(Request $request): Response {

        return response($request->user());

        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
