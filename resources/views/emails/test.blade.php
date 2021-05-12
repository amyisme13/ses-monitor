@component('mail::message')
# Hello

This is a test email.

@component('mail::button', ['url' => url('')])
Homepage
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent