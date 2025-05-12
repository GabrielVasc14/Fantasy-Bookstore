@extends('books.layout')

@section('content')
<div class="container mt-5">

    <div class="profile_banner position-relative mb-5">
        @if($user->banner)
            <img src="{{ asset('storage/' . $user->banner) }}" alt="Banner" class="img-fluid w-100" style="height: 300px; object-fit: cover;">
        @else
            <div class="banner-image" style="background-image: url('{{ asset('images/default-banner.jpg') }}')"></div>
        @endif

        <div class="avatar wrapper">
            <img src="{{ $user->avatar ? asset('storage/' .$user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar">
        </div>
    </div>

    <h2>{{ $user->name }}</h2>
    @if($user->bio)
        <p class="text-muted">{{ $user->bio }}</p>
    @endif
</div>
@endsection
