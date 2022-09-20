@component('mail::message')
# Hello {{ $name }},
You are receiving this email because you are invited to register Proshore Asset Management System.<br>

This register link will expire in 5 days<br>

If you did not request a register request, no further action is required

@component('mail::button', ['url' => $url])
Register Now
@endcomponent

regards,<br>
{{ config('app.name') }}
@endcomponent
