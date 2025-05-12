<div>
    <!-- Filtros -->
    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="search" class="form-control mb-2" placeholder="Search by the title.">
        <input type="text" wire:model.debounce.300ms="detail" class="form-control mb-2" placeholder="Search by the detail.">
        <input type="text" wire:model.debounce.300ms="author" class="form-control mb-2" placeholder="Search by the author.">
        <div class="row mb-3">
            <div class="col">
                <input type="number" wire:model="minPrice" class="form-control" placeholder="Minimum price">
            </div>
            <div class="col">
                <input type="number" wire:model="maxPrice" class="form-control" placeholder="Maximum price">
            </div>
        </div>
    </div>

    <!-- Mensagem de sucesso ao adicionar livro ao carrinho -->
    <div id="successMessage" class="alert alert-success" style="display: none;">
        {{ session('success') }}
    </div>

    <!-- Cards de livros -->
    <div class="row">
        @foreach ($books as $book)
            <div class="col-md-3 d-flex g-4">
                <div class="card text-header h-100 w-100">
                    <img src="{{ $book->image }}" alt="" class="card-img-top">
                    <div class="caption card-body d-flex flex-column">
                        <h4>{{ $book->name }}</h4>
                        <p>{{ $book->detail }}</p>
                        <p>{{ $book->author }}</p>
                        <p>
                            @php $images = json_decode($book->image, true); @endphp
                            @if (!empty($images[0]))
                                <img src="{{ asset('images/' . $images[0]) }}" alt="Book Image" class="img-fluid">
                            @else
                                <span>No image</span>
                            @endif
                        </p>
                        <p><strong>Price: </strong> € {{ $book->price }}</p>
                        <div class="mt-auto">
                            <button wire:click="addToCart({{ $book->id }})" class="btn btn-warning btn-block text-center">
                                <i class="fa-solid fa-cart-plus"></i> Add to cart
                            </button>
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-sm mb-2">
                                <i class="fa-solid fa-list"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>

@section('scripts')
    <script>
        window.addEventListener('book-added', event => {
            const messageDiv = document.getElementById('successMessage');
            messageDiv.style.display = 'block';
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000); // Esconde a mensagem após 3 segundos
        });
    </script>
@endsection
