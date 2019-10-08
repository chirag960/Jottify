@component('mail::message')
<h2>Hey {{$user['name']}}!</h2>
Your registered email-id is {{$user['email']}}. Please click on the below link to verify your email account.

@component('mail::button', ['url' => 'http://jottify.test/user/verify/'.$user->verifyUser->token])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
