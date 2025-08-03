@component('mail::message')
# Welcome, {{ $user->name }}

You’ve been added as a librarian. Click the button below to set your password.

@component('mail::button', ['url' => $url])
Set Your Password
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent