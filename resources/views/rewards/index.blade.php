@extends('books.layout')

@section('content')
<div class="container mt-4">
    <div class="row">

        <div class="row align-items-center mb-3">
            <div class="col">
                <h2>Your Points: <span>{{ $points }}</span></h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('challenges.index') }}" class="btn btn-sm btn-primary"><i class="fas fa-trophy"></i> Challenges</a>
            </div>
        </div>

        @foreach ($rewards as $r)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $r->name }}</h5>
                        <p class="card-text">{{ $r->description }}</p>
                        <p><strong>Cost:</strong> {{ $r->cost_points }} pts</p>
                        <form action="{{ route('rewards.redeem', $r) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-primary mt-auto"
                                @if($points < $r->cost_points) disabled @endif>
                                Redeem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
