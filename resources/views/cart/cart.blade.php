@extends('books.layout')

@section('content')

<div class="d-grip gap-2 d-md-flex justify-content-md-end mb-2">
    <!-- Botao de admin para voltar para o CRUD (se nao for admin o botao n aparece) -->
    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-home"></i> Home</a>
    @endif
    <!-- Botao de admin para voltar para o CRUD - Coupons (se nao for admin o botao n aparece) -->
    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('coupons.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
    @endif
</div>

<!-- Organizacao da tabela dentro do carringo -->
<table class="table table-hover table-condensed">
    <thead>
        <tr>
            <th style="width:50%">Book</th>
            <th style="width:10%">Price</th>
            <th style="width:8%">Quantity</th>
            <th style="width:22%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
    </thead>
    <tbody>
        <!-- Atualizacoes do carrinho (total, subtotal) -->
        @php $total = 0 @endphp
        @if (session('cart'))
            @foreach (session('cart') as $id => $details)
                @php $total += $details['price'] * $details['quantity'] @endphp
                <tr data-id="{{ $id }}">
                    <td data-th="Book">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs">
                                @php
                                    $image = $details['image'];
                                    if (is_array($image)) {
                                        $image = $image[0] ?? 'default.jpg'; // pega a primeira imagem, ou 'default.jpg' se estiver vazio
                                    }
                                @endphp

                                <img src="{{ asset('images/' . $image) }}" width="100" height="150" class="img-responsive"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">€{{ $details['price'] }}</td>
                    <td data-th="Quantity">
                        <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" />
                    </td>
                    <td data-th="Subtotal" class="text-center">€{{ $details['price'] * $details['quantity'] }}</td>
                    <!-- Botao para remover item o carrinho -->
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right">
                @if(session('cart_total'))
                    <h3><strong>Total with discount: €{{ session('cart_total') }}</strong></h3>
                @else
                    <h3><strong>Total: €{{ $total }}</strong></h3></td>
                @endif
            </td>
        </tr>
        <tr>
            <!-- Botoes de voltar ao index da loja, fazer o checkout ou esvaziar o carrinho -->
            <td colspan="5" class="test-right">
                <a href="{{ url('/index_cart') }}" class="btn btn-sm btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a>
                <a href="{{ route('orders.index') }}" class=" btn btn-sm btn-info"><i class="fa-solid fa-history"></i> History</a>
                <a href="{{ route('orders.checkout') }}" class="btn btn-sm btn-success"><i class="fa fa-credit-card"></i> Checkout</a>
            </td>
            <td class="actions" data-th="">
                <button class="btn btn-danger btn-sm remove-all-from-cart text-center"><i class="fa-solid fa-trash"></i>Empty</button>
            </td>
        </tr>

        <div class="row mt-2">
            <div class="col-md-12 mb-4">
                <button class="btn btn-sm btn-primary" id="applyButton"><i class="fa-solid fa-tag"></i> Apply Coupon</button>

                <button class="btn btn-danger btn-sm remove-tag-from-cart"><i class="fa-solid fa-tag"></i> Remove Coupon</button>
            </div>

            @if (session('coupon'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        toastr.success(
                            `Coupon "{{ session('coupon')->code }}" applied!<br>` +
                            `Left uses: {{ session('coupon')->usage_limit - session('coupon')->times_used }}<br>` +
                            `Discount: €{{ session('coupon')->discount }}`,
                            'Coupon Applied',
                            { timeOut: 5000, closeButton: true, progressBar: true, escapeHtml: false }
                        );
                    });
                </script>
            @endif

            <div class="col-md-12 mb-4" id="applyForm" style="display: none;">
                <form  id="applyCouponForm" class="row g-3">
                    @csrf
                    <!-- Form de cupom -->
                    <div class="form-group mb-2 col-md-6">
                        <label for="coupon" class="mr-2">Coupon</label>
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Enter Coupon Code">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm mb-2 ml-3"><i class="fa-solid fa-tag"></i> Apply</button>
                </form>
            </div>
        </div>
    </tfoot>
</table>
@endsection

@section('scripts')
<script type="text/javascript">

    // Funcao para dar update no carrinho
    $(".update-cart").change(function (e) {
        e.preventDefault();

        let ele = $(this);

        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}',
                id: ele.parents("tr").attr("data-id"),
                quantity: ele.parents("tr").find(".quantity").val()
            },
            success: function(response) {
                window.location.reload();
            }
        });
    });

    //Funcao para remover item do carrinho
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();

        let ele = $(this);

        Swal.fire({
            title: 'Remove item?',
            text: "Are you sure that you want to remove this from the cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('remove.from.cart') }}",
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.parents("tr").attr("data-id")
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            text: 'Item removed from the cart.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => window.location.reload(), 1600);
                    },
                    error: function() {
                        Swal.fire('Error', 'Not possible remove the item.', 'error');
                    }
                });
            }
        });
    });


    //Funcao para remover cupom do carrinho
    $(".remove-tag-from-cart").click(function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Remove coupon?',
            text: "Are you sure you want remove?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('remove.tag.from.cart') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Coupon removed!',
                            text: 'Coupon removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => window.location.reload(), 1600);
                    },
                    error: function() {
                        Swal.fire('Error', 'Not possible remove the coupon.', 'error');
                    }
                });
            }
        });
    });


    //Funcao para esvaziar carrinho
    $(".remove-all-from-cart").click(function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Empty cart?',
            text: "Are you sure you want to remove all items from the cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, empty it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('books.destroyAll') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cart emptied!',
                            text: 'All items have been removed from your cart.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => window.location.reload(), 1600);
                    },
                    error: function() {
                        Swal.fire('Error', 'Could not empty the cart.', 'error');
                    }
                });
            }
        });
    });


    //Adicionar evento ao clique do botao de form
    document.getElementById('applyButton').addEventListener('click', function(e) {
        e.preventDefault();

        //Aterna a visibilidade do form
        const applyForm = document.getElementById('applyForm');
        if (applyForm.style.display === 'none' || applyForm.style.display === '') {
            applyForm.style.display = 'block';
        } else {
            applyForm.style.display = 'none';
        }
    });

    //AJAX para apply coupon
    $('#applyCouponForm').submit(function(e) {
        e.preventDefault();

        let coupon = $('#coupon_code').val();

        $.ajax({
            url: "{{ route('apply.coupons') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                coupon_code: coupon
            },
            success: function(response) {
                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr, status, error) {
                let message = xhr.responseJSON?.message ?? 'Failed to apply coupon.';
                toastr.error(message);
            }
        })
    })

    setTimeout(() => {
       document.querySelectorAll('.alert').forEach(element => element.remove());
    }, 2000);
</script>
@endsection
