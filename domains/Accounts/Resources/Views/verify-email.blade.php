<div>
    @if($alreadyValidated ?? false)
        <p>{{ __('Your email was already verified, but thanks anyway for re-confirming it.') }}</p>
    @else
        <p>{{ __('Thank you! You can now login at') . ' ' . config('app.name') }}</p>
    @endif
</div>
