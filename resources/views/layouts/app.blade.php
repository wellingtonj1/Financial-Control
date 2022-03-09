<!DOCTYPE html>
<html>

@include('partials._head')

<body>

    <main class="{{ $mainClass }}">
        <div class="main-inner">
            @yield('content')
        </div>
    </main>

</body>

<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.min.js') }}"></script>

</html>
