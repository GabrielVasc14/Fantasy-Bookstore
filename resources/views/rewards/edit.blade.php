@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Edit Reward</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <!--Botao para voltar ao CRUD -->
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!--Botao para voltar ao CRUD - rewards -->
            <a href="{{ route('rewards.index_crud') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para editar a recompensa selecionada -->
        <form id="editRewardForm">
            @csrf
            @method('PUT')

            <!-- Name edit -->
            <div class="mb-3">
                <label for="inputName" class="form-label"><strong>Name:</strong></label>
                <input
                    type="text"
                    name="name"
                    value="{{ $rewards->name }}"
                    class="form-control @error('name') is-invalid @enderror"
                    id="inputName"
                    placeholder="Name">
                @error('name')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Description edit -->
            <div class="mb-3">
                <label for="inputDescription" class="form-label"><strong>Description:</strong></label>
                <input
                    type="text"
                    name="description"
                    value="{{ $rewards->description }}"
                    class="form-control @error('description') is-invalid @enderror"
                    id="inputDescription"
                    placeholder="Description">
                @error('description')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Points edit -->
            <div class="mb-3">
                <label for="inputCostPoints" class="form-label"><strong>Cost Points:</strong></label>
                <input
                    type="number"
                    name="cost_points"
                    value="{{ $rewards->cost_points }}"
                    class="form-control @error('cost_points') is-invalid @enderror"
                    id="inputCostPoints"
                    placeholder="Ex: 30.00 or 10%">
                @error('cost_points')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Type edit -->
            <div class="mb-3">
                <label for="inputType" class="form-label"><strong>Type:</strong></label>
                <select name="type" id="inputType"value="{{ $rewards->type }}"class="form-control @error('type') is-invalid @enderror" onchange="changeType()">
                    <option value="discount" {{ $rewards->type == 'discount' ? 'selected' : '' }}>Discount</option>
                    <option value="gift" {{ $rewards->type == 'gift' ? 'selected' : '' }}>Gift</option>
                    <option value="free_shipping" {{ $rewards->type == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                </select>
                @error('type')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Value edit -->
            <div class="mb-3">
                <label for="inputValue" class="form-label"><strong>Value:</strong></label>
                <input
                    type="number"
                    name="value"
                    value="{{ $rewards->value }}"
                    class="form-control @error('value') is-invalid @enderror"
                    id="inputValue"
                    placeholder="Ex: 30.00 or 10%"
                    disabled>
                @error('value')
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
        $('#editRewardForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('rewards.update', $rewards->id) }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    toastr.success('Reward updated successfully.');
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Reward has been updated.',
                        timer: 1000,
                        showConfirmButton: false,
                        willClose: () => {
                            document.getElementById('loading-spinner').style.display = 'block';
                            setTimeout(function () {
                                window.location.href = "{{ route('rewards.index_crud') }}";
                            }, 1000);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    toastr.error('Error on update the reward: ' + xhr.responseText);
                }
            });
        });
    });

    function changeType() {
        const selectedType = $('#inputType').val();
        const valueInput = $('#inputValue');

        if (selectedType === 'discount') {
            valueInput.prop('disabled', false);
            valueInput.attr('placeholder', 'Ex: 10.00');
        } else if (selectedType === 'gift') {
            valueInput.prop('disabled', true);
            valueInput.attr('placeholder', 'Gift');
        } else if (selectedType === 'free_shipping') {
            valueInput.prop('disabled', true);
            valueInput.val('');
        }
    }
</script>
@endsection
