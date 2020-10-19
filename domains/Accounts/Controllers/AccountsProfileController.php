<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Factory as Auth;

class AccountsProfileController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(): Response
    {
        return new Response(UserResource::make($this->auth->user()), Response::HTTP_OK);
    }
}
