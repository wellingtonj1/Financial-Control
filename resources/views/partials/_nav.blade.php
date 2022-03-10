@if (Auth::check())

    <div class="side-panel">
        <a href="#" class="btn-side-close">×</a>
		<div class="content">
			<a href="#">Dashboard</a>
			<a href="#">Contas a Pagar</a>
			<a href="#">Contas a Receber</a>
			<a href="#">Conta</a>
		</div>
    </div>

    <nav class="navbar nav-custom">
        <div class="container-fluid">

            <div class="side-nav">
                <a class="open-side-nav" href="#">☰</a>
                <a href="{{ route('welcome') }}" class="navbar-brand">Financial Control</a>
            </div>

            <div class="options">

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Configurações</a></li>
                        <li><a class="dropdown-item logout" href="#">Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
@else
    <nav class="navbar nav-custom">
        <div class="container-fluid">
            <a href="{{ route('welcome') }}" class="navbar-brand">Financial Control</a>
            <div class="options">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Registrar-se</a>
            </div>
        </div>
    </nav>
@endif
