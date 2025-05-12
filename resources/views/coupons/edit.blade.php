@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Edit Coupon</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <!--Botao para voltar ao CRUD -->
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!--Botao para voltar ao CRUD - coupons -->
            <a href="{{ route('coupons.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para editar o cupom selecionado -->
        <form id="editCouponForm">
            @csrf
            @method('PUT')

            <!-- Code edit -->
            <div class="mb-3">
                <label for="inputCode" class="form-label"><strong>Code:</strong></label>
                <input
                    type="text"
                    name="code"
                    value="{{ $coupons->code }}"
                    class="form-control @error('code') is-invalid @enderror"
                    id="inputCode"
                    placeholder="Code">
                @error('code')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Discount edit -->
            <div class="mb-3">
                <label for="inputDiscount" class="form-label"><strong>Discount:</strong></label>
                <input
                    type="number"
                    name="discount"
                    value="{{ $coupons->discount }}"
                    class="form-control @error('discount') is-invalid @enderror"
                    id="inputDiscount"
                    placeholder="Ex: 30.00 or 10%">
                @error('discount')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Expire date edit -->
            <div class="mb-3">
                <label for="inputExpireDate" class="form-label"><strong>Expire Date:</strong></label>
                <input
                    type="date"
                    name="expires_at"
                    value="{{ \Carbon\Carbon::parse ($coupons->expires_at) ->format('Y-m-d') }}"
                    class="form-control @error('expires_at') is-invalid @enderror"
                    id="inputExpireDate"
                    placeholder="Date">
                @error('expires_at')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Min value edit -->
            <div class="mb-3">
                <label for="inputMinCartValue" class="form-label"><strong>Min Value:</strong></label>
                <input
                    type="number"
                    name="min_cart_value"
                    value="{{ $coupons->min_cart_value }}"
                    class="form-control @error('min_cart_value') is-invalid @enderror"
                    id="inputMinCartValue"
                    placeholder="Ex: 30.00">
                @error('min_cart_value')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update</button>
        </form>

    </div>
</div>

<div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        $('#editCouponForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('coupons.update', $coupons->id) }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    toastr.success('Coupon updated successfully.');
                    Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Coupon has been updated.',
                    timer: 1500,
                    showConfirmButton: false,
                    willClose: () => {
                        document.getElementById('loading-spinner').style.display = 'block';
                        setTimeout(function () {
                            window.location.href = "{{ route('coupons.index') }}";
                        }, 1500);
                    }
                });
                },
                error: function (xhr, status, error) {
                    toastr.error('Error on update the coupon: ' + xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
