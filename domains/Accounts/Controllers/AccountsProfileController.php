<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AccountsProfileController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return new Response(
            [
                'data' => UserResource::make(auth()->user())
            ],
            Response::HTTP_OK);
    }
}
