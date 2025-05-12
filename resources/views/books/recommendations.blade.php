@extends('books.layout')

@section('content')
<div class="container mt-4">
    <h2>Recommendations for You</h2>

    @if ($byGenre->isNotEmpty())
        <h4 class="mt-4">Based on your liked genres</h4>
        <div class="row">
            @foreach ($byGenre as $book)
                @include('books._card', ['book' => $book])
            @endforeach
        </div>
    @endif

    @if ($byAuthor->isNotEmpty())
        <h4 class="mt-4">Based on authors you like</h4>
        <div class="row">
            @foreach ($byAuthor as $book)
                @include('books._card', ['book' => $book])
            @endforeach
        </div>
    @endif

    <h4 class="mt-4">Popular books</h4>
    <div class="row">
        @foreach ($popular as $book)
            @include('books._card', ['book' => $book])
        @endforeach
    </div>
</div>
@endsection
