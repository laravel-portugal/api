<?php

namespace Domains\Accounts\Controllers;

use App\Http\Controllers\Controller;
use Domains\Accounts\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class VerifyEmailController extends Controller
{
    protected ?User $user = null;

    public function __invoke(Request $request): ?View
    {
        $this->user = User::find($request->route('id'));
        $hash = \base64_decode($request->route('hash'));

        if (!$this->user || !$this->check($hash, $this->user)) {
            return abort(Response::HTTP_FORBIDDEN);
        }

        if ($this->user->hasVerifiedEmail()) {
            return view('accounts::verify-email')
                ->with('alreadyValidated', true);
        }

        $this->user->markEmailAsVerified();

        return view('accounts::verify-email')
            ->with('alreadyValidated', false);
    }

    public function check(string $hash, User $user): bool
    {
        return Crypt::decrypt($hash) === $user->email;
    }
}
