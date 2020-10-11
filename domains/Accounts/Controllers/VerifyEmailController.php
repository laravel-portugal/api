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

    public function __invoke(Request $request): View
    {
        $this->user = User::findOrFail($request->route('id'));
        $hash = \base64_decode($request->route('hash'));

        if (!$this->user || !$this->check($hash)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($this->user->hasVerifiedEmail()) {
            return view('accounts::users.verify-email')
                ->with('alreadyValidated', true);
        }

        $this->user->markEmailAsVerified();

        return view('accounts::users.verify-email')
            ->with('alreadyValidated', false);
    }

    private function check(string $hash): bool
    {
        return Crypt::decrypt($hash) === $this->user->email;
    }
}
