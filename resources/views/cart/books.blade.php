@extends('books.layout')

@section('content')
<div class="row mt-2">

        <div class="row mt-2">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="col-md-12 mb-3">
                    <!-- Botão para abrir o modal de importação -->
                    <button class="btn btn-outline-success" id="importGoogleBooksBtn">
                        <i class="fa-brands fa-google"></i> Import from Google Books
                    </button>
                </div>
            @endif
        </div>

        <div class="col-md-12 mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fa-solid fa-magnifying-glass"></i> Search Books</button>
        </div>

        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Books</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Formulario de filtros e pesquisas-->
                        <form action="{{ url('/index_cart') }}" method="GET" class="row g-3">
                            <!-- Filtro por titulo -->
                            <div class="form-group mb-2 col-12">
                                <label for="title" class="mr-2">Title</label>
                                <input type="text" name="title" id="title" value="{{ request('title') }}" class="form-control">
                            </div>

                            <!-- Filtro por autor -->
                            <div class="form-group mb-2 col-md-6">
                                <label for="author" class="mr-2">Author</label>
                                <input type="text" name="author" id="author" value="{{ request('author') }}" class="form-control">
                            </div>

                            <!-- Filtro por detalhes(categorias) -->
                            <div class="form-group mb-2 col-md-6">
                                <label for="text" class="mr-2">Category</label>
                                <input type="text" name="detail" id="detail" value="{{ request('detail') }}" class="form-control">
                            </div>

                            <!-- Filtro por preco -->
                            <div class="form-group mb-2 col-md-6">
                                <label for="min_price" class="mr-2">Min Price</label>
                                <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" class="form-control">
                            </div>
                            <div class="form-group mb-2 col-md-6">
                                <label for="max_price" class="mr-2">Max Price</label>
                                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" class="form-control">
                            </div>

                            <!-- Botao de submisao -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-sm btn-primary mb-2 ml-3"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <div class="d-grip gap-2 d-md-flex justify-content-md-start">
        <!-- Checando se o usuario esta logado e se é admin, se for o botao aparece -->
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm mb-2"><i class="fa fa-arrow-left"></i> Back</a>
        @endif
    </div>

    <!-- Mostra os cards dos livros no index -->
    @foreach ($books as $book)
        @php
            $images = json_decode($book->image, true);
        @endphp
        <div class="col-md-3 d-flex g-4">
            <div class="card text-header h-100 w-100">
                <img src="{{ asset('images/' . $images[0]) }}" alt="" class="card-img-top">
                <div class="caption card-body d-flex flex-column">
                    <h4>
                        <a href="{{ route('books.details', $book->id) }}" class="text-decoration-none">{{ $book->name }}</a>
                    </h4>
                    <p>{{ $book->detail }}</p>
                    <p>{{ $book->author }}</p>
                    <p><strong>Price: </strong> € {{ $book->price }}</p>
                    @if ($book->stock !== null && $book->stock <= 5)
                        <span class="badge bg-danger text-white">Last stock: {{ $book->stock }}</span>
                    @elseif ($book->stock !== null && $book->stock <= 10 && $book->stock > 5)
                        <span class="badge bg-warning text-dark">Low stock: {{ $book->stock }}</span>
                    @endif
                    <div class="mt-auto">
                        <button class="btn btn-warning btn-block btn-sm text-center add-to-cart" data-id="{{ $book->id }}">
                            <i class="fa-solid fa-cart-plus"></i> Add to cart</button>
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-sm mb-2">
                            <i class="fa-solid fa-list"></i></a>
                        @auth
                            @if(auth()->user()->likedBooks()->where('book_id', $book->id)->exists())
                                <button type="button" class="btn btn-danger btn-sm toggle-wishlist" data-id="{{ $book->id }}">
                                    <i class="fa-solid fa-heart-broken"></i> Remove from wishlist
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-sm toggle-wishlist" data-id="{{ $book->id }}">
                                    <i class="fa-regular fa-heart"></i> Add to wishlist
                                </button>
                            @endif
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm mb-2">
                                <i class="fa-regular fa-heart"></i> Login to add to wishlist
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Paginate -->
    <div class="text-center mt-4">
        <button id="loadMoreBtn" class="btn btn-outline-primary">Mostrar mais livros</button>
        {!! $books->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>

</div>

    <!--Modal para fazer a pesquisa na API-->
    <div class="modal fade" id="googleBooksModal" tabindex="-1" aria-labelledby="googleBooksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="googleBooksModalLabel">Search Google Books</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('books.search.google') }}" method="GET">
                        <div class="form-group mb-2">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Search by Title">
                        </div>
                        <div class="form-group mb-2">
                            <label for="Author">Author</label>
                            <input type="text" name="author" id="author" class="form-control" placeholder="Search by Author">
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-magnifying-glass"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        //Adicionando ao cart
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const bookId = this.getAttribute('data-id');

                fetch(`/add-to-cart/${bookId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Book added to the cart.');
                        window.location.reload();
                    } else {
                        toastr.error('Error on add the book to the cart.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    toastr.error('Error in AJAX requisition,')
                });
            });
        });

        //Adicionando a wishlist
        $(document).ready(function () {
            $('.toggle-wishlist').on('click', function () {
                let button = $(this);
                let bookId = button.data('id');

                $.ajax({
                    url: `/books/toggle-like/${bookId}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.liked) {
                            button.html('<i class="fa-solid fa-heart-broken"></i> Remove from wishlist');
                            button.removeClass('btn-primary').addClass('btn-danger');
                            toastr.success('Book added to the wishlist.');
                        } else {
                            button.html('<i class="fa-regular fa-heart"></i> Add to wishlist');
                            button.removeClass('btn-danger').addClass('btn-primary');
                            toastr.success('Book removed to the wishlist.');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Something went wrong. Please try again.');
                    }
                });
            });
        });

        //Exibir o modal ao clicar no botao "Import from google"
        document.getElementById('importGoogleBooksBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('googleBooksModal'));
            modal.show();
        });

        //Exibir mais livros
        let nextPageUrl = "{{ $books->nextPageUrl() }}";

        document.getElementById('loadMoreBtn').addEventListener('click', function() {
            if (!nextPageUrl) return;

            fetch(nextPageUrl)
                .then(res => res.text())
                .then(data => {
                    const parser = new DOMParser();
                    const htmlDoc = parser.parseFromString(data, 'text/html');

                    // Seleciona os novos cards da próxima página
                    const newBooks = htmlDoc.querySelectorAll('.col-md-3');
                    const container = document.querySelector('.row.mt-2');

                    newBooks.forEach(book => {
                        container.appendChild(book);
                    });

                    // Atualiza a próxima URL
                    const newPagination = htmlDoc.querySelector('.pagination');
                    const nextLink = newPagination?.querySelector('a[rel="next"]');
                    nextPageUrl = nextLink?.getAttribute('href') ?? null;

                    // Se não houver mais páginas, esconde o botão
                    if (!nextPageUrl) {
                        document.getElementById('loadMoreBtn').style.display = 'none';
                    }
                })
                .catch(err => {
                    console.error(err);
                    toastr.error('Erro ao carregar mais livros.');
                });
        });

    </script>
@endsection
