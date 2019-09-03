@extends('layouts.app')

@section('content')

<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}" class="text-blue">Home</a>
            @else
                <a href="{{ route('login') }}" class="text-blue">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-blue">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md text-blue">
            404 | Not Found
        </div>
    </div>
</div>


@endsection