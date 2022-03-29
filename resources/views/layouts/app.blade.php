<!DOCTYPE html>
<html>

@include('partials._head')

@php
	$mainClass = $mainClass ?? '';
	$user = Auth::user();
@endphp

<body>
    
    @include('partials._nav')

        <div class="wrapper">

            @if ($mainClass != 'welcome' && $mainClass != 'pass_reset' && $mainClass != 'login' && $mainClass != 'register' 
                && $mainClass != 'password-reset' && $mainClass != 'email-password' && $mainClass != 'confirm-password')
                
                @auth
                    <main class="{{ $mainClass }}">
                        <div class="main-inner">
                            @yield('content')
                        </div>
                    </main>
                @endauth
                
            @else
                <main class="{{ $mainClass }}">
                    <div class="main-inner">
                        @yield('content')
                    </div>
                </main>
            @endif
    
        </div>
    </div>

    <footer>
        <section class="disclaimer">
            Copyright © 2021 Project BI Inc. Todos os direitos reservados. Wellington Junio de M F Ltda.
        </section>
    </footer>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.min.js') }}"></script>
    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
    
</body>
</html>
