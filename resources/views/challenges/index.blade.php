@extends('books.layout')

@section('content')
<div class="row">

    <div class="row align-items-center mb-3">
        <div class="col">
            <h2>Challenges</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('rewards.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-angle-left"></i> Back</a>
        </div>
    </div>

    @foreach ($challenges as $ch)
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h5>{{ $ch->title }}</h5>
                <p>{{ $ch->description }}</p>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: {{ $ch->pivot->progress / $ch->target *100 }}%;">
                        {{ $ch->pivot->progress }} / {{ $ch->target }}
                    </div>
                </div>
                @php
                    $can = $ch->progress < $ch->target;
                @endphp
                @if(!$ch->pivot->completed)
                    <form action="{{ route('challenges.increment', $ch->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-primary" @if(!$can) disabled @endif><i class="fa fa-check"></i> Check</button>
                    </form>
                @else
                    <span class="badge bg-success">Completed!</span>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
