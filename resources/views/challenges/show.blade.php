@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Show Callenge</h2>
    <div class="card-body">

        <div class="row">
            <div class="gap-10 d-md-flex justify-content-md-between">
                <!-- Botao para voltar ao CRUD -->
                <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
                <!-- Botao de voltar para o CRUD - Reward -->
                <a href="{{ route('challenges.index_crud') }}" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>

        <!-- Detalhes do item selecionado -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Title:</strong> <br/>
                    {{ $challenges->title }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Description:</strong> <br/>
                    {{ $challenges->description }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Type:</strong> <br/>
                    {{ $challenges->type }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Target:</strong> <br/>
                    {{ $challenges->target }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Period:</strong> <br/>
                    {{ $challenges->period }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
