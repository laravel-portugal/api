<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AccountsLoginController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token    = $user->createToken($user->email)->accessToken;
            $response = ['access_token' => $token];
            return new Response($response, Response::HTTP_OK);
        }

        return new Response(['message' => 'The authentication credentials are wrong'], Response::HTTP_UNAUTHORIZED);
    }
}
