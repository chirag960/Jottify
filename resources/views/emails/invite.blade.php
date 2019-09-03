<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
</head>
<body>
    <div class="well col-sm-8">
    Hey {{ $params['user_name'] }} ! You have been invited to the project "{{ $params['project_title'] }}" . Click <a href="localhost:8000/project/{{ $params['project_id'] }}">
    here</a>
    to accept the invitation and see the project details.
    </div>
</body>
</html>