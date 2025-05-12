@php $img = json_decode($book->image, true)[0] ?? 'default.jpg'; @endphp
<div class="col-md-3 d-flex g-4">
    <div class="card text-header h-100 w-100">
        <img src="{{ asset('images/' . $img) }}" class="card-img-top" alt="{{ $book->name }}">
        <div class="caption card-body d-flex flex-column">
            <h5 class="card-title"><a href="{{ route('books.details', $book->id) }}" class="text-decoration-none">{{ $book->name }}</a></h5>
            <p class="mt-auto"><strong> {{ $book->author }}</strong></p>
            <p class="card-text text-truncate">{{ Str::limit($book->detail, 50) }}</p>
            <p class="mt-auto"><strong>€{{ $book->price }}</strong></p>
            @if ($book->stock !== null && $book->stock <= 5)
                <span class="badge bg-danger text-white">Last stock: {{ $book->stock }}</span>
            @elseif ($book->stock !== null && $book->stock <= 10 && $book->stock > 5)
                <span class="badge bg-warning text-dark">Low stock: {{ $book->stock }}</span>
            @endif
    <small class="text-muted">{{ $book->order_items_count }} Sold</small>
        </div>
    </div>
</div>
