@component('mail::message')

{{ $message}}

@component('mail::button', ['url' => 'http://jottify.test/project/'.$id])
Go to Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
