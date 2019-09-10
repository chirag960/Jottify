@extends('layouts.app')

@section('styles')

#toast-container {
    top: auto !important;
    right: auto !important;
    bottom: 10%;
    left:7%;  
  }
@endsection
@section('full-content')
<div class="auth-bg">
        <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4">
                <h5 class="card-title col s12 center-align">{{ __('Reset Password') }}</h5>
                <div class="card-body row">
                    <form class="col s10 m8" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="row">
                                <div class="input-field col s12 m12 l12 xl12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <label for="email">{{ __('E-mail') }}</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                        </div>

                        <div class="center-align">
                        
                                <button type="submit" class="btn waves-effect waves-light light-blue">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                  
                    </form>
                </div>
            </div>
</div>

@if (session('status'))
<script>
   M.toast({html: {{ session('status') }}, classes: 'rounded'});
</script>
@endif
@endsection
