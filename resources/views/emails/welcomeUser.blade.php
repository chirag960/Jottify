@component('mail::message')
<h2>Welcome to the site {{$user['name']}}!</h2>
Thank you for registering! Now start creating your projects.

@component('mail::button', ['url' => 'http://jottify.test/home'])
Create a Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
