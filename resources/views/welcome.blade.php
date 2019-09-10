<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Jottify</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!--script src="{{ asset('js/app.js') }}" defer></script-->
        <script src="{{ asset('js/materialize.min.js') }}" defer></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Dancing+Script&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/materialize.min.css') }}" rel="stylesheet">
        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <style>
            html, body {
                height: 100vh;
            }
            .appName{
                font-family: 'Dancing Script', cursive;
                /*font-family: 'Dr Sugiyama', cursive;*/
            }

            .nav-wrapper {
            background-color: #0288d1  !important;
            font-size: 14px;
            font-weight: bold;
            }

            .navbar-brand{
                position: absolute;
                left: 5%;
                margin-left: -5px !important;
                display: block;
                font-size: 2.4rem;
            }

            @media only screen and (min-width: 601px) {
            .navbar-brand {
                left: 1%;
                margin-left : 1% !important;
            }
            }

            @media only screen and (max-width: 600px) {
            .navbar-brand {
                left:1%;
                margin-left: 1% !important;
            }
            }

            @media all and (min-width: 699px) and (max-width: 520px), (max-width: 1151px) {
                h3 {
                    font-size: 2em;
                }
                .responsive-image {
                    width:90%;
                    height:auto;
                }
                }

            .content{
            }

            .content1 {
                text-align: center; 
                background-color: #0d47a1; /* For browsers that do not support gradients */
                background-image: linear-gradient(-90deg, #0d47a1, #5e35b1); /* Standard syntax (must be last) */
                color:white;
                font-family: 'Nunito','sans-serif';
                padding:3%;
            }

            .content2 {
                text-align: center; 
                background-color: #d32f2f; /* For browsers that do not support gradients */
                background-image: linear-gradient(-90deg, #d32f2f, #ff6d00); /* Standard syntax (must be last) */
                color:white;
                font-family: 'Nunito','sans-serif';
                padding:3%;
            }

            .content3 {
                text-align: center; 
                background-color: #c5e1a5; /* For browsers that do not support gradients */
                background-image: linear-gradient(-90deg, #c5e1a5, #33691e); /* Standard syntax (must be last) */
                color:white;
                font-family: 'Nunito','sans-serif';
                padding:3%;
            }

            ul > li > a {
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                font-family: 'Nunito','sans-serif';
                text-decoration: none;
            }

            ul > li > a:hover {
                color:white;
            }

            .responsive-image{
                width:400px;
                height: 400px;
            }

            .row{
                margin:0;
            }

            a{
                text-decoration: none !important;
                font-family: 'Nunito','sans-serif';
            }

            a:hover{
                text-decoration: none !important;
                color:white;
            }

            .side-btn{
                margin-left:5px;
                margin-bottom:10px;
            }
        </style>
    </head>
    <body>
            <div class="navbar-fixed">
            <nav>
            <div class="nav-wrapper">
                <a href="/" class="navbar-brand appName">Jottify</a>
                <ul id="nav-mobile" class="right ">
                        @if (Route::has('login'))
                            @auth
                                <li title="home"><a href="{{ url('/home') }}" class="text-blue"><i class="material-icons">home</i></a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-blue">Login</a></li>
        
                                @if (Route::has('register'))
                                    <li><a href="{{ route('register') }}" class="text-blue">Register</a></li>
                                @endif
                            @endauth
                    @endif
                </ul>
            </div>
        </nav>
        </div>
        <div class="content">
        <div class="content1 row">
            <div class="col s12 m6"><h3 class="center-align">
                Having problems in organising team and managing projects. Don't Panic now! <span class="appName">Jottify</span> is here for you.
            </h3></div>
            <div class="col s12 m6">
                <img class="responsive-image" src="{{ asset('media/icons/panic.jpg')}}">
            </div>
                
        </div>
            <div class="content2 row">
                <div class="col s12 m6">
                    <img class="responsive-image" src="{{ asset('media/icons/HappyTeam.jpg')}}">
                </div>
                <div class="col s12 m6">
                    <h3 class="center-align">
                        With <span class="appName">Jottify</span> you can organize your project and assign tasks to your team mates. Get started with doing your projects in a fun way.
                    </h3>
                </div>      
        </div>
        <div class="content3 row">
                <div class="col s12 m6 center-align">
                    <h3>So what are you waiting for? Register now.</h3>
                    <br/>
                    <span class="side-btn"><a class="waves-effect hoverable waves-light btn-large green" href="{{ route('login') }}">Login</a></span>
                    <span class="side-btn"><a class="waves-effect hoverable waves-light btn-large light-blue pulse" href="{{ route('register') }}">Register</a></span>
                </div>      
        </div>
        </div>
        </div>
        </div>
    </body>
</html>
