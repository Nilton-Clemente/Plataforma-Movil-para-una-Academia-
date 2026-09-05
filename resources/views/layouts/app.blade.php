<!doctype html>
<html lang="es" data-bs-theme="light">
<head>
    <title>@yield('title', 'Academia Update')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @php
        $cartCount = auth()->check()
            ? (int) auth()->user()->cart?->items()->sum('quantity')
            : 0;
    @endphp

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Academia Update" width="80">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-lg-flex justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('about')) active @endif" href="{{ route('about') }}">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('gallery')) active @endif" href="{{ route('gallery') }}">Galeria</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('contact')) active @endif" href="{{ route('contact') }}">Contactos</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link @if(request()->routeIs('login')) active @endif" href="{{ route('login') }}">Iniciar sesion</a></li>
                        
                    @else
                        <li class="nav-item"><a class="nav-link @if(request()->routeIs('store.index')) active @endif" href="{{ route('store.index') }}">Tienda</a></li>
                        <li class="nav-item">
                            <a class="nav-link cart-nav-link @if(request()->routeIs('cart.*')) active @endif" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3"></i>
                                Carrito
                                @if($cartCount > 0)
                                    <span class="badge rounded-pill text-bg-success">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Panel</a></li>
                        @if(auth()->user()?->is_admin)
                            <li class="nav-item"><a class="nav-link @if(request()->routeIs('admin.books.*')) active @endif" href="{{ route('admin.books.index') }}">Libros</a></li>
                            <li class="nav-item"><a class="nav-link @if(request()->routeIs('admin.blog.*')) active @endif" href="{{ route('admin.blog.index') }}">Blog</a></li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="ms-lg-2">
                                @csrf
                                <button class="btn btn-outline-light btn-sm">Salir</button>
                            </form>
                        </li>
                        
                        
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @if (session('status'))
        <div class="container alert-wrap">
            <div class="alert alert-success">{{ session('status') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="container alert-wrap">
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @yield('content')

    <footer class="bg-dark text-white py-3 mt-5">
        <p class="text-center mb-0">Todos los derechos reservados &copy; a Nilton Clemente.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
