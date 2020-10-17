<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountsLoginController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new Response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }
}
