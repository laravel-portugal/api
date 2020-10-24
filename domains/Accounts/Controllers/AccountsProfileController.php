<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Resources\UserResource;
use Illuminate\Http\Request;

class AccountsProfileController extends Controller
{
    public function __invoke(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
