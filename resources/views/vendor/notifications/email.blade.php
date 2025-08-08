{{-- resources/views/vendor/notifications/email.blade.php --}}

@component('mail::message')
    # Reset lozinke

    Kliknite na dugme ispod da resetujete svoju lozinku:

    @component('mail::button', ['url' => $actionUrl])
        Resetuj lozinku
    @endcomponent

    Ako niste vi tražili reset lozinke, nema potrebe za daljom akcijom.

    Hvala,<br>
    {{ config('app.name') }}
@endcomponent
