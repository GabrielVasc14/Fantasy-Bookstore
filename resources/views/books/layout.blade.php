<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fantasy!!</title>
    <link rel="icon" href="{{ asset('images/estante-com-fout-books.png') }}" type="image/png">

    <!-- Arquivos de estilo -->
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Livewire -->
    @livewireStyles

</head>

<style type="text/css">
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Adicionando rolagem no dropdown quando há muitos itens */
    .dropdown-menu {
        padding: 20px;
        box-shadow: 0px 5px 30px black;
        margin-top: 10px;
        max-width: 90vw;
        max-height: 50vh;  /* Define a altura máxima do dropdown */
        overflow-y: auto;  /* Adiciona rolagem vertical */
    }

    /* A seta que aponta para o botão */
    .dropdown-menu:before{
        content: " ";
        position:absolute;
        top:-20px;
        left:270px;
        border:10px solid transparent;
        border-bottom-color:#fff;
    }
    .fs-8 {
        font-size: 13px;
    }
</style>

<body>

    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand text-white fs-4 fw-bold" href="{{ url('/index_cart') }}">Livraria Fantasy!</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse p-3" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ps-2">
                    <li class="nav-item">
                        <button id="theme-toggle-btn" class="btn btn-sm btn-outline-info mt-2 me-2" onclick="toggleTheme()">
                            <i class="fa-solid fa-moon"></i>
                        </button>
                    </li>
                    <li class="dropdown ms-auto mb-2 mb-lg-0">
                        <a class="btn btn-outline-info btn-sm dropdown-toggle mt-2 me-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle"></i> {{ optional(Auth::user())->name ?? 'Guest'}}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="nav-item">
                                @auth
                                    <p><strong>{{ Auth::user()->points }}</strong> points</p>
                                @endauth
                            </li>
                            <li class="nav-item ms-auto">
                                @auth
                                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-info mt-2 me-2"><i class="fas fa-user-circle me-1"></i> My Profile</a>
                                @endauth
                            </li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-info mt-2 me-2"><i class="fa-solid fa-user"></i> Account</a>
                                    <a href="{{ route('cart.wishlist') }}" class="btn btn-sm btn-outline-info mt-2 me-2"><i class="fa-solid fa-heart"></i> Wishlist</a>
                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-2"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/index_cart') }}" class="btn btn-sm btn-outline-info mt-2 me-2"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('books.recommendations') }}" class="btn btn-sm btn-outline-info mt-2 me-2"><i class="fa-solid fa-star"></i> Recomendations</a>
                    </li>
                    <li class="nav-item d-flex-md-end">
                        <a href="{{ route('books.bestRated') }}" class="btn btn-sm btn-outline-info mt-2 me-2"><i class="fa-solid fa-book"></i> Best Rated</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-info mt-2 me-2" href="{{ route('rewards.index') }}"><i class="fa-solid fa-gift"></i> Rewards</a>
                    </li>
                </ul>

                <!-- Botão de Carrinho com dropdown -->
                <div class="dropdown ms-auto mb-2 mb-lg-0">
                    <button class="btn btn-outline-info btn-sm dropdown-toggle mt-2 me-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart <span class="badge badge-pill badge-danger">{{ count((array) session('cart')) }}</span>
                    </button>

                    <!-- Menu Dropdown do Carrinho -->
                    <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="dropdownMenuButton">
                        <!-- Total no Carrinho -->
                        <div class="row total-header-section">
                            <div class="col-6">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="badge badge-pill badge-danger">{{ count((array) session('cart')) }}</span>
                            </div>
                            @php $total = 0 @endphp
                            @foreach ((array) session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                            @endforeach
                            <div class="col-6 text-end">
                                <p><strong>Total: <span class="text-info">{{ number_format($total, 2) }}</span></strong></p>
                            </div>
                        </div>

                        <!-- Botão para visualizar o carrinho -->
                        <div class="text-center">
                            <a href="{{ route('cart') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View All</a>
                        </div>

                        <!-- Itens do Carrinho -->
                        @if(session('cart'))
                            @foreach (session('cart') as $id => $details)
                                <div class="row cart-detail pb-3 pt-2">
                                    <div class="col-4">
                                        <img src="{{ asset('images/' . $details['image']) }}" class="img-fluid rounded" />
                                    </div>
                                    <div class="col-8 cart-detail-book">
                                        <p class="mb-0">{{ $details['name'] }}</p>
                                        <span class="fs-8 text-info">Price: €{{ number_format($details['price'], 2) }}</span><br/>
                                        <span class="fs-8 fw-lighter">Quantity: {{ $details['quantity'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    // Função para limpar cookies do Tawk.to
    function clearTawkCookies() {
        document.cookie = "TawkConnectionTime=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "Tawk_Token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "TawkVisitorID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    // Função para limpar dados de localStorage e sessionStorage
    function clearTawkStorage() {
        localStorage.removeItem('TawkConnectionTime');
        localStorage.removeItem('Tawk_Token');
        localStorage.removeItem('TawkVisitorID');
        sessionStorage.removeItem('TawkConnectionTime');
        sessionStorage.removeItem('Tawk_Token');
        sessionStorage.removeItem('TawkVisitorID');
    }

    // Função para resetar o estado do visitante no Tawk.to
    function resetVisitor() {
        clearTawkCookies();  // Limpar cookies
        clearTawkStorage();  // Limpar localStorage e sessionStorage

        Tawk_API.setAttributes({
            'name': '',
            'email': '',
            'visitorID': 'guest_' + new Date().getTime()  // Gera um novo ID para o visitante
        });

        Tawk_API.maximize(); // Maximiza o chat automaticamente
    }

    window.onload = function() {
        // Verifica se a sessão de reset do Tawk.to deve ser feita
        @if(session('reset_tawk'))
            resetVisitor();  // Reseta a sessão do Tawk.to
        @endif

        // Verifica se o usuário está logado e atribui as informações do visitante ao Tawk.to
        @if(Auth::check())
            var Tawk_API = Tawk_API || {};
            // Gerando um visitorID único
            Tawk_API.visitor = {
                name: "{{ Auth::user()->name }}",
                email: "{{ Auth::user()->email }}",
                visitorID: "{{ Auth::user()->id }}_{{ strtotime(now()) }}"  // Usando ID do usuário e timestamp para garantir um ID único
            };
        @endif

        Tawk_API.maximize(); // Maximiza o chat automaticamente
    };

    // Carregamento do script Tawk.to
    var Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6821a5965d7d30190c98f609/1ir1mtl3q';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->

</body>

<script>
    // CSRF Token para todos os forms em ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Script de notificação de sucesso
    window.addEventListener('load', function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function() {
                alert.classList.remove('show');
            }, 1000);
        }
    });


    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    // Função para alternar entre o modo claro e escuro
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');

        // Alternando o ícone entre lua e sol
        const icon = document.getElementById('theme-toggle-btn').querySelector('i');
        if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }

</script>

@yield('scripts')

<!-- Livewire -->
@livewireScripts

</html>
