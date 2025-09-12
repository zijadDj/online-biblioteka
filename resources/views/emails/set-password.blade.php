@component('mail::message')
    # Resetovanje lozinke

    Kliknite na dugme ispod da resetujete lozinku:

    @component('mail::button', ['url' => $resetUrl])
        Resetuj lozinku
    @endcomponent

    Ako niste tražili reset lozinke, slobodno ignorišite ovaj email.

    Pozdrav,<br>
    {{ config('app.name') }}
@endcomponent

