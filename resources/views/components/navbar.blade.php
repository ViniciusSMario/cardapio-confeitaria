<nav class="navbar navbar-expand-lg navbar-dark bg-maroon ">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="img-fluid rounded-circle">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('categories') }}">Categorias</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#sobre">Sobre nós</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#depoimentos">Depoimentos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('meus_pedidos')}}">Meus Pedidos</a></li>
            </ul>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('shop') }}">
                        <i class="fas fa-shopping-basket"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                            0
                        </span>
                    </a>
                </li>                
                @if (Auth::user() && Auth::user()->type == 'admin')    
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-user"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
