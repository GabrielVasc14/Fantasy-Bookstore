@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Add new challenge</h2>
    <div class="card-body">

        <!-- Apenas admin tem acesso a essa pagina -->
        <!-- Botao para voltar ao CRUD -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!-- Botao para voltar ao CRUD dos challenges -->
            <a href="{{ route('challenges.index_crud') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para adicionar Challenge -->

        <div class="mb-3">
            <label for="inputTitle" class="form-label"><strong>Title:</strong></label>
            <input
                type="text"
                name="title"
                id="inputTitle"
                class="form-control @error('title') is-invalid @enderror"
                placeholder="Title">
            @error('title')
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
            <label for="inputType" class="form-label"><strong>Type:</strong></label>
            <select name="type" class="form-select @error('type') is-invalid @enderror" id="inputType">
                <option value="">Select Type</option>
                <option value="reading">Reeding</option>
                <option value="reviews">Reviews</option>
                <option value="purchases">Purchases</option>
                <option value="generic">Generic</option>
            </select>
            @error('type')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="inputTarget" class="form-label"><strong>Target:</strong></label>
            <input
                type="number"
                name="target"
                class="form-control @error('target') is-invalid @enderror"
                id="inputTarget"
                placeholder="Ex: 10">
            @error('target')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="inputPeriod" class="form-label"><strong>Period:</strong></label>
            <select name="period" class="form-select @error('period') is-invalid @enderror" id="inputPeriod">
                <option value="">Select Period</option>
                <option value="daily">Daily</option>
                <option value="weakly">Weakly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
            @error('period')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success" onclick="createChallenge(event)"><i class="fa-solid fa-floppy-disk"></i> Submit</button>

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
    //Funcao para criar o challenge
    function createChallenge(event) {
        event.preventDefault();

        const formData = new FormData();
            formData.append('title', $('#inputTitle').val());
            formData.append('description', $('#inputDescription').val());
            formData.append('type', $('#inputType').val());
            formData.append('target', $('#inputTarget').val());
            formData.append('period', $('#inputPeriod').val());

            formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('challenges.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    toastr.success('Challenge created successfully!');
                    Swal.fire({
                        title: 'Success!',
                        text: 'Challenge created successfully!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        willClose: () => {
                            document.getElementById('loading-spinner').style.display = 'block';
                            setTimeout(function() {
                                window.location.href = "{{ route('challenges.index_crud') }}";
                            }, 1000);
                        }
                    });
                } else {
                    toastr.error('Error creating challenge!');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error creating challenge: ' + xhr.responseText);
            }
        });
    }
</script>
@endsection
