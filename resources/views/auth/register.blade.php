@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
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
                                    <span class="invalid-feedback hideMessage" id="invalidEmail" role="alert"></span>
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
@endsection

@section('links')
@error('email')
<script>
M.toast({html: "{{ $message }}", classes: 'rounded'});
</script>
@enderror
<script type="text/javascript" src="{{ asset('js/register.js') }}"></script>
@endsection
