@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Livraria Fantasy - ADMIN</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissable fade-show" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
        <!-- Botao para ir a pagina para criar livros -->
            <a href="{{ route('challenges.index_crud') }}" class="btn btn-warning btn-sm"> <i class="fa fa-trophy"></i> Challenges</a>
        <!-- Botao para ir a pagina de rewards -->
            <a href="{{ route('rewards.index_crud') }}" class="btn btn-warning btn-sm"> <i class="fa fa-star"></i> Rewards</a>
        <!-- Botao para ir a pagina de cupons -->
            <a href="{{ route('coupons.index') }}" class="btn btn-warning btn-sm"> <i class="fa fa-ticket"></i> Coupons</a>
        <!-- Botao para ir a pagina para criar livros -->
            <a href="{{ route('books.create') }}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> Add New Book</a>
        </div>

        <!-- Tabela do CRUD -->
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">Number</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Price</th>
                    <th>Author</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            @php
                                $images = json_decode($book->image, true); // Decodificando o JSON para um array
                            @endphp

                            @if (!empty($images))
                                @foreach ($images as $image)
                                    <img src="{{ asset('images/' . $image) }}" alt="Book Image" width="100px" />
                                @endforeach
                            @else
                                <span>No image</span>
                            @endif
                        </td>
                        <td>{{ $book->name }}</td>
                        <td>{{ $book->detail }}</td>
                        <td>{{ $book->price }}</td>
                        <td>{{ $book->author }}</td>
                        <td>
                        <!-- deletar livro, mostrar, editar e adicionar ao carrinho -->
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-sm mb-2"><i class="fa-solid fa-list"></i></a>

                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-sm mb-2"><i class="fa-solid fa-pen-to-square"></i></a>

                            <a href="{{ route('add.to.cart', $book->id) }}" class="btn btn-warning btn-block btn-sm text-center mb-2" role="button"><i class="fa-solid fa-cart-plus"></i></a>

                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-id="{{ $book->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">There are no data.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        {!! $books->withQueryString()->links('pagination::bootstrap-5') !!}

    </div>
</div>
@endsection

@section('scripts')

<script>
    // Timer para as notificacoes desaparecerem automaticamente apos um tempo
    window.addEventListener('load', function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function() {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }, 2000);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const bookId = this.getAttribute('data-id');
            const csrfToken = '{{ csrf_token() }}';
            const clickedButton = this;

            if (!bookId) {
                toastr.error('Erro interno: ID do livro não encontrado.');
                return;
            }

            Swal.fire({
                title: 'Delete Book?',
                text: 'Do you really want to delete the book?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/books/destroy/${bookId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            const row = clickedButton.closest('tr');
                            if (row) row.remove();
                            toastr.success('Book deleted successfully.');
                        } else {
                            toastr.error('Error on delete the book.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        toastr.error('Error trying to delete the book.');
                    });
                }
            });
        });
    });
});


</script>

@endsection
