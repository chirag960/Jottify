@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('full-content')
<div class="auth-bg">
            <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4">
                
                <h4 class="card-title col s12 center-align">{{ __('Login') }}</h4>
                <div class="card-body row">
                    <form class="col s10 m8" method="POST" action="{{ route('login') }}" onsubmit= "return validateForm()">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                            <i class="material-icons prefix">email</i>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <label for="email">{{ __('E-Mail Address') }}</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    <span class="invalid-feedback hideMessage" id="invalidEmail" role="alert"></span>
                                </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                            <i class="material-icons prefix">vpn_key</i>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            <label for="password">{{ __('Password') }}</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span class="invalid-feedback hideMessage" id="invalidPwd" role="alert"></span>
                            </div>
                        </div>

                        <div class="switch">
                                <label>
                                  <input type="checkbox" {{ old('remember') ? 'checked' : '' }} />
                                  <span class="lever" style="font-size:1.2rem !important"></span>
                                  Remember me!
                                </label>
                              </div>
                        <div class="center-align">
                            <button type="submit" class="btn waves-effect waves-light light-blue">
                                {{ __('Login') }}
                            </button>
                            <br/>
                            <br/>
                        </div>
                        <div width="100%">
                            @if (Route::has('password.request'))
                            <span><a href="{{ route('password.request') }}" class="left">
                                {{ __('Forgot Your Password?') }}
                            </a></span>
                            @endif
                            <span><a href="{{ route('register') }}" class="right">
                                {{ __('New User? Register now') }}
                            </a></span>
                        </div>
                    </form>
                </div>
            </div>
</div>
@if (session('status'))
<script>
     M.toast({html:"{{ session('status') }}", classes: 'rounded'});
</script>
@endif
@if (session('warning'))
    <script>
        M.toast({html:"{{ session('warning') }}",classes: 'rounded'});
    </script>
@endif


<script>

function validateEmail(){
    var email = document.getElementById("email").value;
    var ele = document.getElementById("invalidEmail");

    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
    
    if(email.length == 0){
        ele.innerHTML = "<strong>Please enter a email id</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))){
        ele.innerHTML = "<strong>The email id is not valid</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}


function validatePassword(){
    var pwd = document.getElementById("password").value;
    var ele = document.getElementById("invalidPwd");
    if(pwd.length == 0){
        ele.innerHTML = "<strong>Please enter a password</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(pwd.length < 8 || pwd.length > 20){
        ele.innerHTML = "<strong>Length should not be less than 8 or greater than 20</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}

function validateForm(){
    var email = validateEmail();
    var pwd = validatePassword();
    if(email && pwd) 
    {
        console.log("all valid");
        return true;
    }
    else return false;
}
</script>
@endsection
