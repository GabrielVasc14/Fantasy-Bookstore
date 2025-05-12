@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Show Coupon</h2>
    <div class="card-body">

        <div class="row">
            <div class="gap-10 d-md-flex justify-content-md-between">
                <!-- Botao para voltar ao CRUD -->
                <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
                <!-- Botao de voltar para o CRUD - Cupons -->
                <a href="{{ route('coupons.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>

        <!-- Detalhes do item selecionado -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Code:</strong> <br/>
                    {{ $coupons->code }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Discount:</strong> <br/>
                    {{ $coupons->discount }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Expire Date:</strong> <br/>
                    {{ $coupons->expires_at }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Min Value:</strong> <br/>
                    {{ $coupons->min_cart_value }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
