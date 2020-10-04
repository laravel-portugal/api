<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class VerifyEmailController extends Controller
{
    protected ?User $user = null;

    public function __invoke(Request $request): Response
    {
        $this->user = User::find($request->route('id'));

        if (!$this->user || !$this->check($request)) {
            return \response('', Response::HTTP_FORBIDDEN);
        }

        if ($this->user->hasVerifiedEmail()) {
            return \response('', Response::HTTP_GONE);
        }

        $this->user->markEmailAsVerified();

        return \response('', Response::HTTP_NO_CONTENT);
    }

    public function check(Request $request)
    {
        if (!Hash::check($this->user->getEmailForVerification(), base64_decode((string)$request->route('hash')))) {
            return false;
        }

        return true;
    }
}
