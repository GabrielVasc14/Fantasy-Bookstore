@extends('books.layout')

@section('content')

<div class="row mt-2">
    <h1>My Wishlist</h1>

    @foreach ($likedBooks as $book)
        @php
            $images = json_decode($book->image, true);
        @endphp
        <div class="col-md-3 d-flex g-4">
            <div class="card text-header h-100 w-100">
                <img src="{{ asset('images/' . $images[0]) }}" alt="" class="card-img-top">
                <div class="caption card-body d-flex flex-column">
                    <h4>
                        <a href="{{ route('books.details', $book->id) }}" class="text-decoration-none text-dark">{{ $book->name }}</a>
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
                        <form action="{{ route('books.dislike', $book->id) }}" method="POST" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-2 remove-from-wishlist" data-id="{{ $book->id }}">
                                <i class="fa-solid fa-heart-broken"></i> Remove from wishlist
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection

@section('scripts')

    <script>
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

        $(document).ready(function () {
            $('.remove-from-wishlist').on('click', function () {
                let button = $(this);
                let bookId = button.data('id');

                $.ajax({
                    url: `/books/dislike/${bookId}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            toastr.success('Book removed to the wishlist.');
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Something went wrong. Please try again.');
                    }
                });
            });
        });

    </script>
@endsection

