<div>
    @if($alreadyValidated ?? false)
        <p>{{ __('Your email was already verified, but thanks anyway for re-confirming it.') }}</p>
    @else
        <p>{{ __('Thank you! Your email is now verified!') }}</p>
    @endif
    <p>You may login at <a href="{{ config('accounts.login_url') }}">Laravel Portugal</a>.</p>
</div>
