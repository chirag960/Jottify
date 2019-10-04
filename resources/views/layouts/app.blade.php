<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Jottify') }}</title>

    <!-- Scripts -->
    
    <!--script src="{{ asset('js/app.js') }}"></script-->
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/materialize.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    @yield('style-link')

</head>
<body>
    <div class="navbar-fixed">
        <nav>
                <div class="nav-wrapper">
                <a class="navbar-brand appName" href="{{ url('/home') }}">
                    {{ config('app.name', 'Jottify') }}
                </a>

                <!-- Right Side Of Navbar -->
                <ul class="right">
                    @guest
                        <!-- Authentication Links -->
                        <li>
                            <a class="white-text" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li>
                                <a class="white-text" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li>
                            <form>
                                <div class="input-field">
                                    <input id="searchBar" type="search" autocomplete="off" placeholder="Search projects/tasks">
                                    <label class="label-icon" for="searchBar"><i class="material-icons">search</i></label>
                                    <i class="material-icons">close</i>
                                </div>
                                <div id="results"></div>
                            </form>
                        </li>
                        <li>
                            <a class='dropdown-trigger' href='#' data-target='profileOptions'>
                            <img id="navbarProfile" src="{{Auth::user()->photo_location}}" title="{{Auth::user()->name." (".Auth::user()->email.")" }}" class="circle avatar-image responsive-img">
                            </a>
                            <ul id='profileOptions' class='dropdown-content'>
                                <li><a href="/profile" class="profileDrop">Profile Settings</a></li>
                                <li>
                                        <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();" class="profileDrop">
                                        {{ __('Logout') }} 
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
    </div>
<div class="full-content row">
    @yield('full-content')
</div>

<div id="overlay">
    <div class="preloader-wrapper active" id="spinner">
      <div class="spinner-layer spinner-blue">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-yellow">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-green">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('js/materialize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/navbar.js') }}"></script>
@yield('links')

</body>
</html>
