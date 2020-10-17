<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Resources\UserResource;
use Illuminate\Http\Response;

class AccountsProfileController extends Controller
{
    public function __invoke(): Response
    {
        return new Response(
            [
                'data' => UserResource::make(auth()->user())
            ],
            Response::HTTP_OK);
    }
}
