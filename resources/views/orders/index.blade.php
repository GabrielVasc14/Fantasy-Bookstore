@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">My Orders</h2>

    <div class="d-grip gap-2 d-md-flex justify-content-md-end mb-2 mt-2 md-2">
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

    @foreach ($orders as $order)
        @php
            $subtotal = 0;

            //Calcula o total do carrinho
            foreach ($order->items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $discount = 0;

            if ($order->coupon) {
                $discount = $order->coupon->discount;
            }

            $total = $subtotal - $discount;
        @endphp

        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Order #{{ $order->id }} - Total: €{{ number_format($total, 2) }}</span>
            </div>
            <div class="card-body">
                <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }} - {{ $order->cep }}</p>
                <ul class="list-group list-group-flush">
                    @foreach ($order->items as $item)
                        <li class="list-group-item">{{ $item->book->name }} x{{ $item->quantity }} (€{{ $item->price }})</li>
                    @endforeach
                </ul>
                <br>
                @if ($discount > 0)
                    <p><strong>Subtotal: </strong>€{{ number_format($subtotal, 2) }}</p>
                    <p><strong>Discount: </strong>-€{{ number_format($discount, 2) }}</p>
                    <p><strong>Coupon Code: </strong>{{ $order->coupon->code }}</p>
                @endif

                @if (!$order->is_paid)
                    <form action="{{ route('orders.pay', $order->id) }}" method="POST" class="d-inline mt-2">
                        @csrf

                        <button type="submit" class="btn btn-sm btn-warning"><i class="fa fa-wallet"></i> Pay</button>
                    </form>
                @else
                    <span class="badge bg-success"><i class="fa fa-check-circle"></i> Payed</span>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
