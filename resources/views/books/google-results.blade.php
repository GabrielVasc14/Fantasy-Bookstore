@extends('books.layout')

@section('content')
<div class="row mt-2">
    <h2>Google Books - Results</h2>

    @if (isset($message))
        <div class="alert alert-warning">
            {{ $message }}
        </div>
    @endif

    @foreach ($books as $book)
        <div class="card mb-3">
            <img src="{{ $book['cover'] }}" alt="Capa de {{ $book['title'] }}" class="card-img-top" style="max-width:120px;" onerror="this.onerror=null;this.src='{{ $book['fallback'] }}';"/>

            <div class="card-body">
                <h5>{{ $book['title'] }}</h5>
                <p><strong>Author:</strong> {{ implode(', ', $book['authors']) }}</p>
                <p><strong>Details:</strong> {{ implode(', ', $book['details']) }}</p>
                <p><strong>Synopsis:</strong> {{ $book['synopsis'] }}</p>
                <p><strong>Price:</strong> €{{ $book['price'] }}</p>
                <form action="{{ route('books.import.google') }}" method="POST">
                    @csrf
                    <input type="hidden" name="google_book_id" value="{{ $book['id'] }}">
                    <input type="hidden" name="name" value="{{ $book['title'] }}">
                    <input type="hidden" name="author" value="{{ implode(', ', $book['authors']) }}">
                    <input type="hidden" name="detail" value="{{ implode(', ', $book['details']) }}">
                    <input type="hidden" name="synopsis" value="{{ $book['synopsis'] }}">
                    <input type="hidden" name="images" value="{{ $book['cover'] }}">
                    <input type="hidden" name="price" value="{{ $book['price'] }}">

                    <button type="submit" class="btn btn-sm btn-success mt-2">Import</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
