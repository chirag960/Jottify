<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="vieport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Jottify') }}</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/materialize.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/ajax.js') }}"></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/materialize.min.css') }}" rel="stylesheet">

    <!-- For date time picker -->
    <!--script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" /-->
    
    <!-- Compiled and minified Material CSS >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <Compiled and minified Material JavaScript>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script-->

    <!-- For more links -->
    @yield('links')

    <style>

    html,body{
        background-color: #f3f3f3;
        display: flex;
        min-height: 100vh;
        flex-direction: column;
    }

    main {
    flex: 1 0 auto;
    }
    .appName{
        font-family: 'Dancing Script', cursive;
    }

    .nav-wrapper {
        background-color: #0288d1  !important;
        font-size: 14px;
        font-weight: bold;
    }

    .navbar-brand{
       position: absolute;
        left: 1%;
        margin-left: -1px !important;
        display: block;
        font-size: 2.4rem;
    }

    .home-icon-div{
        float:left;
        background-color: lightskyblue;
        border-radius: 5%;
    }

    .home-icon-div:hover{
        opacity:0.8;
        cursor: pointer;
    }

    .home-icon{
        color:white;
    }

    @media only screen and (max-width: 600px) {
        #searchBar{
            padding:0px 5px !important;
            background-color:white !important;
            border-radius:5px 5px !important;
            width:200px !important;
        }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 600px) {
        #searchBar {
            padding:0px 5px !important;
            background-color:white !important;
            border-radius:5px 5px !important;
            width:400px !important;
        }
} 

    .nav-menu{
        display: inline-block;
    }

    .nav-menu li{
        display: inline;
    }
    .full-content{
        margin:0;
        padding:0;
        height:100%;
        margin-top:20px;
    }
    .auth-bg{
        background:#f3f3f3;
        width:100%;
    }

    .active {
        color:#0288d1 !important;
    }

    #description:focus{
        border-bottom-color:#0288d1 !important;
    }

    .input-field input:focus {
        border-bottom: 1px solid #0288d1 !important;
        box-shadow: 0 1px 0 0 #0288d1 !important;
   }
/*
   input[type=checkbox]
    {
        -webkit-appearance:checkbox !important;
    }
*/
/* 
    .switch label input[type=checkbox]:checked+.lever {
        background-color: #0288d1 !important;
    } */

    .full-content{
    }
    .inspire{
        padding-top:2%;
    }

    .dropdown-content{
        top: 100% !important;
    }
    .stick{
        padding:0px !important;
        margin:0px !important;
    }

    .title-list{
        float:none;
    }
    .results{
        height: 400px !important; 
        overflow: auto !important; 
    }
    .profileDrop{
        color:black !important;
        text-decoration:none !important;
    }
    #dropTaskList{
        height: 400px !important; 
        overflow: auto !important; 
    }
    @media only screen and (max-width: 600px) {
        .avatar-image{
            width:30px;
            height:30px;
            position:fixed;
            top:15px;
            right:15px;
        }
    } 

    @media only screen and (min-width: 600px) {
        .avatar-image{
            width:40px;
            height:40px;
        }
    } 
    
    @yield('styles');

    </style>

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
                                    <input type="text" id="searchBar" name="search" data-target="dropTaskList" autocomplete="off" placeholder="Search projects or tasks..." onblur="removeSearchPattern()">
                                    <label class="label-icon" for="search"></label>
                                    <div id="results" style="display: block;"></div>
                            </form>
                        </li>
                        <li>
                            <a class='dropdown-trigger' href='#' data-target='profileOptions'>
                                <!--img src="{{asset("media/user_profile_photo/default.jpg")}}" alt="" class="circle avatar-image responsive-img"--> <!-- notice the "circle" class -->
                            <img id="navbarProfile" src="{{Auth::user()->photo_location}}" alt="" class="circle avatar-image responsive-img"> <!-- notice the "circle" class -->
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
<script type="text/javascript" src="{{ asset('js/navbar.js') }}"></script>
<script>
function removeSearchPattern(){
    document.getElementById("searchBar").value = "";
}

$(document).on("click", function(event){
            var $trigger = $("#results");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $("#results").removeAttr("style").hide();
                $("#searchBar").html("");
            }            
        });
</script>

{{-- <footer class="page-footer light-blue darken-2">
<div class="inspire col s10 offset-s1 m10 offset-m1 l8 offset-l2 center-align">
    <span class="text-white"><h6><i>{{ \Illuminate\Foundation\Inspiring::quote() }}</i></h6></span>
</div>
</footer> --}}

</body>
</html>
