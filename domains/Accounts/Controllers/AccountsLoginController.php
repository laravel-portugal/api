<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Factory as Auth;


class AccountsLoginController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(Request $request): Response
    {
       $credentials = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!$token = $this->auth->attempt($credentials)) {
            return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new Response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }
}
