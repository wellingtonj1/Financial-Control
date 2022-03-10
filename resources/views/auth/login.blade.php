@extends('layouts.app', ['mainClass' => 'login'])

@section('content')

    <div class="pic col-md-7">
        <img src="{{ asset('assets/img/bkg-login.jpg') }}" class="show">

    </div>

    <div class="login-container">

        <div class="card side-row col-md-6 offset-md-6">

            <div class="card-header text-center">
                <h3>Seja Bem Vindo</h3>
                <small>Realize o login para acessar o sistema</small>
            </div>

            <div class="card-body pd-up-50">

                @include('partials._alert', ['oneError' => true])
                
                <form method="POST" action="{{ route('login') }}">

                    @csrf

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                {{ __('Lembre de mim') }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mg-up-25">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>

@endsection
