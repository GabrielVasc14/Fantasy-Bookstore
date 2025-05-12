@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Add New Coupon</h2>
    <div class="card-body">

        <!-- Apenas admin tem acesso a essa pagina -->
        <!-- Botao para voltar ao CRUD -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
        <!-- Botao para voltar ao CRUD dos cupons -->
            <a href="{{ route('coupons.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para adicionar cupons -->

        <div class="mb-3">
            <label for="inputCode" class="form-label"><strong>Code:</strong></label>
            <input
                type="text"
                name="code"
                id="inputCode"
                class="form-control @error('code') is-invalid @enderror"
                placeholder="Code">
            @error('code')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputDiscount" class="form-label"><strong>Discount:</strong></label>
            <input
                type="number"
                name="discount"
                class="form-control @error('discount') is-invalid @enderror"
                id="inputDiscount"
                placeholder="Ex: 30.00 or 10%">
            @error('discount')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputExpireDate" class="form-label"><strong>Expire Date:</strong></label>
            <input
                type="date"
                name="expires_at"
                class="form-control @error('expires_at') is-invalid @enderror"
                id="inputExpireDate"
                placeholder="Date">
            @error('expires_at')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputMinCartValue" class="form-label"><strong>Min Value:</strong></label>
            <input
                type="number"
                name="min_cart_value"
                class="form-control @error('min_cart_value') is-invalid @enderror"
                id="inputMinCartValue"
                placeholder="Ex: 30.00">
            @error('min_cart_value')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success" onclick="createCoupon(event)"><i class="fa-solid fa-floppy-disk"></i> Submit</button>

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
    function createCoupon(event) {

        console.log("coupon");

        event.preventDefault();

        const formData = new FormData();
            formData.append('code', $('#inputCode').val());
            formData.append('discount', $('#inputDiscount').val());
            formData.append('expires_at', $('#inputExpireDate').val());
            formData.append('min_cart_value', $('#inputMinCartValue').val());

            formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('coupons.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Coupon added successfully');
                    Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: 'Coupon has been created.',
                    timer: 1500,
                    showConfirmButton: false,
                    willClose: () => {
                            document.getElementById('loading-spinner').style.display = 'block';
                            setTimeout(function () {
                                window.location.href = "{{ route('coupons.index') }}";
                            }, 1000);
                        }
                    });
                } else {
                    toastr.error('Error on add coupon: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error on add coupon:', error);
            }
        });
    }
</script>
@endsection
