@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Show Reward</h2>
    <div class="card-body">

        <div class="row">
            <div class="gap-10 d-md-flex justify-content-md-between">
                <!-- Botao para voltar ao CRUD -->
                <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
                <!-- Botao de voltar para o CRUD - Reward -->
                <a href="{{ route('rewards.index_crud') }}" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>

        <!-- Detalhes do item selecionado -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Name:</strong> <br/>
                    {{ $rewards->name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Description:</strong> <br/>
                    {{ $rewards->description }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Cost Points:</strong> <br/>
                    {{ $rewards->cost_points }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Tyoe:</strong> <br/>
                    {{ $rewards->type }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Value:</strong> <br/>
                    {{ $rewards->value }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
