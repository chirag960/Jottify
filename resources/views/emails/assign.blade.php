@component('mail::message')

A new Task {{$title}} has been assigned to you. Check it out.
@component('mail::button', ['url' => 'http://site.test/project/'.$project_id.'/task/'.$id])
Go to Task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
