@extends('layouts.app')

@section('styles')
.hideMessage{
    display:none;
}
@endsection

@section('full-content')
<div class="auth-bg">
        <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4">
                <h4 class="card-title col s12 center-align">{{ __('Register') }}</h4>
                <div class="card-body row">
                    <form class="col s10 m8" method="POST" action="{{ route('register') }}" onsubmit= "return validateForm()">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            <label for="name">{{ __('Name') }}</label>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    <span class="invalid-feedback hideMessage" id="invalidName" role="alert"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                            <i class="material-icons prefix">email</i>
                            <input id="email" type="email" class="formcontrol @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            <label for="email">{{ __('E-Mail Address') }}</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    {{-- <span class="invalid-feedback hideMessage" id="invalidEmail" role="alert"></span> --}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6 l6 xl6">
                            <i class="material-icons prefix">vpn_key</i>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <label for="password">{{ __('Password') }}</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    <span class="invalid-feedback hideMessage" id="invalidPassword" role="alert"></span>
                            </div>
                            <div class="input-field col s12 m6 l6 xl6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            </div>
                        </div>

                        <div class="center-align">
                                <button type="submit" class="btn waves-effect waves-light light-blue">
                                    {{ __('Register') }}
                                </button>
                                <br/>
                                <br/>
                                <a href="{{ route('login') }}" class="">
                                    {{ __('Already have an account? Login') }}
                                </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<script>

function validateName(){
    var name = document.getElementById("name").value;
    var ele = document.getElementById("invalidName");
    if(name.length == 0){
        ele.innerHTML = "<strong>Please enter a name</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(name.length < 3 || name.length > 20){
        ele.innerHTML = "<strong>Length should not be more less than 3 or greater than 30</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}

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
    var ele = document.getElementById("invalidPassword");
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
    return true;

}

function validateConfirmPassword(){
    var ele = document.getElementById("invalidPassword");
    if(document.getElementById("password-confirm").value != document.getElementById("password").value){
        document.getElementById("invalidPassword").innerHTML = "<strong>The confirmed password doesn't match with the password<strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}

function validateForm(){
    var name = validateName();
    var email = validateEmail();
    var pwd = validatePassword();
    //var cpwd = validateConfirmPassword();
    var cpwd = true;
    if(name && email && pwd && cpwd) 
    {
        console.log("all valid");
        return true;
    }
    else return false;
}
</script>
@endsection
