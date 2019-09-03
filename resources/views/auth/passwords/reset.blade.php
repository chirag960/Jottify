@extends('layouts.app')

@section('styles')

.centro{
    text-align:center;
}

@endsection

@section('full-content')
<div class="auth-bg">
        <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4">
                <h4 class="card-title col s12 center-align">Reset Password</h4>
                <div class="card-body row">
                    <form method="POST" class="col s10 m8" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row">
                            <div class="input-field col s12">
                            <i class="material-icons prefix">email</i>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
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
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                            </div>
                        </div>

                        
                        <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn waves-effect waves-light light-blue">
                                    {{ __('Reset Password') }}
                                </button>
                        </div>
             
                    </form>
                </div>
    </div>
</div>
@endsection
