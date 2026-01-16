@component('mail::message')
# Dear {{$data['name']}},
We've received a request to reset the password for this account. No changes have been made to your account yet.

You can reset your password by clicking below button:
@component('mail::button', ['url' => $data['url']])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}

