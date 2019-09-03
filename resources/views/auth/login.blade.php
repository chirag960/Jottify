@extends('layouts.app')

@section('full-content')
<div class="auth-bg">
            <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4">
                <h4 class="card-title col s12 center-align">{{ __('Login') }}</h4>
                <div class="card-body row">
                    <form class="col s10 m8" method="POST" action="{{ route('login') }}">
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
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                    </form>
                </div>
            </div>
            <div class="col s10 offset-s1 m8 offset-m2 l4 offset-l4 center-align">
            <a class="white btn waves-effect waves-green blue-text" href="{{ route('register')}}">Register</a>
            </div>
</div>
@endsection
