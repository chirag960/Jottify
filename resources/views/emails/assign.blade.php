<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
</head>
<body>
    <div class="well col-sm-8">
    Hey {{ $params['user_name'] }} ! You have been assigned a new task in the project "{{ $params['project_title'] }}" . Click <a href="localhost:8000/project/{{ $params['project_id'] }}/task/{{ $params['title']}}">
    here</a>
    to see the task details.
    </div>
</body>
</html>