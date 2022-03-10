@extends('layouts.app', ['mainClass' => 'report-index'])

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                *CLEAN PROJECT*

                <a href="{{ Route('login') }}">Login</a>
                <a href="{{ Route('register') }}">Registrar-se</a>

            </div>
            
        </div>

    </div>

@endsection
