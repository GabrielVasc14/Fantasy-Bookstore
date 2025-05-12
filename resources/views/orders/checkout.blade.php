@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Finish Order</h2>
    <div class="card-body">

        <div class="d-grip gap-2 d-md-flex justify-content-md-end mb-2">
            <!-- Botao de admin para voltar para o CRUD (se nao for admin o botao n aparece) -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('books.index') }}" class="btn btn-warning btn-sm"><i class="fa fa-book"></i> Books - CRUD</a>
            @endif
            <!-- Botao de admin para voltar para o CRUD - Coupons (se nao for admin o botao n aparece) -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('coupons.index') }}" class="btn btn-warning btn-sm"><i class="fa fa-ticket"></i> Coupons - CRUD</a>
            @endif
            <!-- Botao para voltar a home page -->
            <a href="{{ url('/index_cart') }}" class="btn btn-primary btn-sm"><i class="fa fa-home"></i> Home</a></a>
            <!-- Botao para retornar ao cart -->
            <a href="{{ route('cart') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a></a>

        </div>

        <!-- Form para checkout -->
        <form action="{{ route('orders.store') }}" method="POST" class="row g-4">
            @csrf

            <div class="col-6">
                <div class="form-floating mb-3">
                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-control form-control-sm"
                        placeholder="Address">
                        @error('address')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    <label for="address" class="col-sm-2 col-form-label mr-2">Address</label>
                </div>

                <div class="form-floating mb-3">
                    <input
                        type="text"
                        name="city"
                        id="city"
                        class="form-control form-control-sm"
                        placeholder="City">
                        @error('city')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    <label for="city" class="col-sm-2 col-form-label mr-2">City</label>
                </div>

                <div class="form-floating mb-3">
                    <input
                        type="text"
                        name="cep"
                        id="cep"
                        class="form-control form-control-sm"
                        placeholder="CEP">
                        @error('cep')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    <label for="cep" class="col-sm-2 col-form-label mr-2">CEP</label>
                </div>
            </div>

            <div class="col-6">
                <img src="{{ asset('images/fantasy.png') }}" alt="Livraria Fantasy" class="d-block mx-auto img-fluid rounded shadow-sm" style="max-width: 250px;">
            </div>

            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-truck"></i> Finish Order</button>
        </form>
    </div>
</div>
@endsection
