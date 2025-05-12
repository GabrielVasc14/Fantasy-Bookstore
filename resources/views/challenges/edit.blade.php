@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Edit challenge</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <!--Botao para voltar ao CRUD -->
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!--Botao para voltar ao CRUD - rewards -->
            <a href="{{ route('challenges.index_crud') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para editar a recompensa selecionada -->
        <form id="editChallengeForm">
            @csrf
            @method('PUT')

            <!-- Title edit -->
            <div class="mb-3">
                <label for="inputTitle" class="form-label"><strong>Title:</strong></label>
                <input
                    type="text"
                    name="title"
                    value="{{ $challenges->title }}"
                    class="form-control @error('title') is-invalid @enderror"
                    id="inputTitle"
                    placeholder="Title">
                @error('title')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Description edit -->
            <div class="mb-3">
                <label for="inputDescription" class="form-label"><strong>Description:</strong></label>
                <input
                    type="text"
                    name="description"
                    value="{{ $challenges->description }}"
                    class="form-control @error('description') is-invalid @enderror"
                    id="inputDescription"
                    placeholder="Description">
                @error('description')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Type edit -->
            <div class="mb-3">
                <label for="inputType" class="form-label"><strong>Type:</strong></label>
                <select name="type" id="inputType" class="form-control @error('type') is-invalid @enderror">
                    <option value="reading" {{ $challenges->type == 'reading' ? 'selected' : '' }}>Reading</option>
                    <option value="reviews" {{ $challenges->type == 'reviews' ? 'selected' : '' }}>Reviews</option>
                    <option value="purchases" {{ $challenges->type == 'purchases' ? 'selected' : '' }}>Purchases</option>
                    <option value="generic" {{ $challenges->type == 'generic' ? 'selected' : '' }}>Generic</option>
                </select>
                @error('type')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Target edit -->
            <div class="mb-3">
                <label for="inputTarget" class="form-label"><strong>Target:</strong></label>
                <input
                    type="text"
                    name="target"
                    value="{{ $challenges->target }}"
                    class="form-control @error('target') is-invalid @enderror"
                    id="inputTarget"
                    placeholder="Target">
                @error('target')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!--Period edit -->
            <div class="mb-3">
                <label for="inputPeriod" class="form-label"><strong>Period:</strong></label>
                <select name="period" id="inputPeriod"value="{{ $challenges->period }}"class="form-control @error('period') is-invalid @enderror">
                    <option value="daily" {{ $challenges->period == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weakly" {{ $challenges->period == 'weakly' ? 'selected' : '' }}>Weakly</option>
                    <option value="monthly" {{ $challenges->period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $challenges->period == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
                @error('period')
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
        $('#editChallengeForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('challenges.update', $challenges->id) }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    toastr.success('Challenge updated successfully!');
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Challenge has been updated.',
                        timer: 1000,
                        showConfirmButton: false,
                        willClose: () => {
                            document.getElementById('loading-spinner').style.display = 'block';
                            setTimeout(function () {
                                window.location.href = "{{ route('challenges.index_crud') }}";
                            }, 1000);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    toastr.error('Error updating challenge.');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
