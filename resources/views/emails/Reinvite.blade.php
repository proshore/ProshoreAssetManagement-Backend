@component('mail::message')
    # Hello!
    You are receiving this email because you are re-invited to join the team

    This register link will expire in 5 days

    If you did not request a register request, no further action is required

@component('mail::button', ['url' => ''])
Register Now
@endcomponent

Thanks,<br>
{{--{{ config('app.name') }}--}}
    Proshore
@endcomponent
