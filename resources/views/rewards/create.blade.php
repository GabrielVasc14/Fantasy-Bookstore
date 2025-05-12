@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-heder">Add New Reward</h2>
    <div class="card-body">

        <!-- Apenas admin tem acesso a essa pagina -->
        <!-- Botao para voltar ao CRUD -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!-- Botao para voltar ao CRUD dos Reward -->
            <a href="{{ route('rewards.index_crud') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para adicionar Reward -->

        <div class="mb-3">
            <label for="inputName" class="form-label"><strong>Name:</strong></label>
            <input
                type="text"
                name="name"
                id="inputName"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Name">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputDescription" class="form-label"><strong>Description:</strong></label>
            <input
                type="text"
                name="description"
                class="form-control @error('description') is-invalid @enderror"
                id="inputDescription"
                placeholder="Description">
            @error('description')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputCostPoints" class="form-label"><strong>Cost Points:</strong></label>
            <input
                type="number"
                name="cost_points"
                class="form-control @error('cost_points') is-invalid @enderror"
                id="inputPoints"
                placeholder="Ex: 30.00 or 10%">
            @error('cost_points')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputType" class="form-label"><strong>Type:</strong></label>
            <select name="type" class="form-select @error('type') is-invalid @enderror" id="inputType" onchange="changeType()">
                <option value="">Select Type</option>
                <option value="discount">Discount</option>
                <option value="gift">Gift</option>
                <option value="free_shipping">Free Shipping</option>
            </select>
            @error('type')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputValue" class="form-label"><strong>Value:</strong></label>
            <input
                type="number"
                name="value"
                class="form-control @error('value') is-invalid @enderror"
                id="inputValue"
                placeholder="Ex: 30.00 or 10%"
                disabled>
            @error('value')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success" onclick="createReward(event)"><i class="fa-solid fa-floppy-disk"></i> Submit</button>

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
    //Funcao para criar o Reward
    function createReward(event) {
        console.log("reward");

        event.preventDefault(); // Impede o envio padrão do formulário

        const formData = new FormData();
            formData.append('name', $('#inputName').val());
            formData.append('description', $('#inputDescription').val());
            formData.append('cost_points', $('#inputPoints').val());
            formData.append('type', $('#inputType').val());
            formData.append('value', $('#inputValue').val());

            formData.append('_token', '{{ csrf_token()}}');

        $.ajax({
            url: "{{ route('rewards.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    toastr.success('Reward created successfully!');
                    Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: 'Reward created successfully!',
                    timer: 1500,
                    showConfirmButton: false,
                    willClose: () => {
                            //Exibe o loading spinner
                            document.getElementById('loading-spinner').style.display = 'block';
                            setTimeout(function() {
                                window.location.href = "{{ route('rewards.index_crud') }}"; // Redireciona para a página de listagem
                            }, 1000);
                        }
                    });
                } else {
                    toastr.error('Error creating reward: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                toastr.error('An error occurred while creating the reward.');
            }
        });
    }

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
