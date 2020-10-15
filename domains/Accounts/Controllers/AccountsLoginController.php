<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountsLoginController extends Controller
{

    public function __invoke(Request $request): Response
    {
        $this->validate($request, [
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (Hash::check($request->password, $user->password)) {
            $token    = $user->createToken($user->email . '-' . Request()->ip())->accessToken;
            $response = ['access_token' => $token];
            return new Response($response, 200);
        } else {
            $response = ["message" => "Password mismatch"];
            return new Response($response, 422);
        }
    }
}
