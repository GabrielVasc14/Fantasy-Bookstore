<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Livraria Fantasy!!</title>
    <link rel="icon" href="{{ asset('images/estante-com-fout-books.png') }}" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="{{ asset('css/layout_profile.css') }}" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-dark">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand text-white fw-bold" href="{{ url('/') }}">
                <i class="fa fa-book"></i> Livraria Fantasy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse justify-content-end" id="navbarContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item px-2">
                        <a class="nav-link text-white" href="{{ url('/index_cart') }}">Books</a>
                    </li>
                    @guest
                        <li class="nav-item px-2">
                            <a class="nav-link text-white" href="{{ route('register') }}">Register</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown px-2">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Espaço abaixo da navbar -->
    <div style="padding-top: 70px;">
        <main class="container">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2025 Livraria Fantasy. Todos os direitos reservados.</p>
    </footer>

</body>
</html>
