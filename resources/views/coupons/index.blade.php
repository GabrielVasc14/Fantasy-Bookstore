@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Livraria Fantasy - ADMIN - Coupons</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->

        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <!-- Botao para voltar ao CRUD -->
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!-- Botao para ir ao index dos cupons -->
            <a href="{{ route('coupons.admin_index') }}" class="btn btn-warning btn-sm"> <i class="fa fa-plus"></i> Index - Coupon</a>
            <!-- Botao para ir a pagina para criar os cupons -->
            <a href="{{ route('coupons.create') }}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> Add New Coupon</a>
        </div>

        <!-- Tabela do crud -->
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">Number</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Expire Data</th>
                    <th>Min Value</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($coupons as $coupon)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->discount }}</td>
                        <td>{{ $coupon->expires_at }}</td>
                        <td>{{ $coupon->min_cart_value }}</td>
                        <td>
                            <!-- Form para deletar, editar e mostrar cupons -->
                            <a href="{{ route('coupons.show', $coupon->id) }}" class="btn btn-info btn-sm md-2"><i class="fa-solid fa-list"></i></a>

                            <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm md-2"><i class="fa-solid fa-pen-to-square"></i></a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-id="{{ $coupon->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">There are no data.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        {!! $coupons->withQueryString()->links('pagination::bootstrap-5') !!}

    </div>
</div>

@endsection

@section('scripts')
<script>
    // Timer para as notificacoes desaparecerem automaticamente apos um tempo
    window.addEventListener('load', function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function() {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }, 2000);
        }
    });

    // Funcao para deletar os cupons
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const couponId = this.getAttribute('data-id');
                const csrfToken = '{{ csrf_token() }}';
                const clickedButton = this;

                if (!couponId) {
                    toastr.error('Internal error: Coupon ID not found.');
                    return;
                }

                Swal.fire({
                    title: 'Delete coupon?',
                    text: 'Are you sure you want to delete this coupon?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/coupons/destroy/${couponId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Coupon has been deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => window.location.reload(), 1600);
                            } else {
                                toastr.error('Failed to delete the coupon.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error trying to delete the coupon.');
                        });
                    }
                });
            });
        });
    });

</script>
@endsection
