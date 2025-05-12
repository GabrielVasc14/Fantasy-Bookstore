@extends('books.layout')

<style>
    .custom-file-upload {
        display: inline-block;
        padding: 10px 20px;
        cursor: pointer;
        background-color: #007bff;
        color: #fff;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    .custom-file-upload:hover {
        background-color: #0056b3;
    }

    #inputImage {
        display: none;
    }
</style>

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Add New Book</h2>
    <div class="card-body">

        <!-- Apenas admins acessao essa pagina -->
        <!-- Botao para voltar ao CRUD -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para adicionar livros -->

        <div class="mb-3">
            <label for="inputName" class="form-label"><strong>Name:</strong></label>
            <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                id="inputName"
                placeholder="Name">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputDetail" class="form-label"><strong>Detail:</strong></label>
            <textarea
                style="height:150px"
                name="detail"
                class="form-control @error('detail') is-invalid @enderror"
                id="inputDetail"
                placeholder="Detail"></textarea>
            @error('detail')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputSynopsis" class="form-label"><strong>Synopsis:</strong></label>
            <textarea
                style="height:150px"
                name="synopsis"
                class="form-control @error('synopsis') is-invalid @enderror"
                id="inputSynopsis"
                placeholder="Synopsis"></textarea>
            @error('synopsis')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputImage" class="form-label"><strong>Image:</strong></label>
            <label for="inputImage" class="custom-file-upload">
                Selecionar Imagens
            </label>
            <input
                type="file"
                name="image[]"
                id="inputImage"
                multiple required
                accept="image/jpeg, image/png, image/jpg, image/gif, image/webp">

            @error('image')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputStock" class="form-label"><strong>Stock:</strong></label>
            <input
                type="number"
                name="stock"
                step="0.01"
                class="form-control @error('stock') is-invalid @enderror"
                id="inputStock"
                placeholder="Ex: 29.90">
            @error('stock')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputCondition" class="form-label"><strong>Condition:</strong></label>
                <select name="condition" id="condition" class="form-control @error('condition') is-invalid @enderror">
                    <option value="">Select Condition</option>
                    <option value="New">New</option>
                    <option value="Used">Used</option>
                    <option value="Limited Edition">Limited Edition</option>
                    <option value="Signed">Signed</option>
                </select>
            @error('price')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="is_special_edition" class="form-label">
                <input
                    type="checkbox"
                    name="is_special_edition"
                    id="is_special_edition"
                    class="form-check-input"
                    {{ old('is_special_edition', $books->is_special_edition ?? false) ? 'checked' : '' }}>Is Special Edition?
            </label>
        </div>

        <div class="mb-3">
            <label for="inputPrice" class="form-label"><strong>Price:</strong></label>
            <input
                type="number"
                name="price"
                step="0.01"
                class="form-control @error('price') is-invalid @enderror"
                id="inputPrice"
                placeholder="Ex: 29.90">
            @error('price')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputPriceEbook" class="form-label"><strong>Price (Ebook):</strong></label>
            <input
                type="number"
                name="price_ebook"
                step="0.01"
                class="form-control @error('price_ebook') is-invalid @enderror"
                id="inputPriceEbook"
                placeholder="Ex: 29.90">
            @error('price_ebook')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputPriceAudio" class="form-label"><strong>Price (AudioBook):</strong></label>
            <input
                type="number"
                name="price_audio"
                step="0.01"
                class="form-control @error('price_audio') is-invalid @enderror"
                id="inputPriceAudio"
                placeholder="Ex: 29.90">
            @error('price_audio')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputAuthor" class="form-label"><strong>Author:</strong></label>
            <input
                type="text"
                name="author"
                class="form-control @error('author') is-invalid @enderror"
                id="inputAuthor"
                placeholder="Author">
            @error('author')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="inputAudio" class="form-label"><strong>Audiobook (MP3):</strong></label>
            <input
                type="file"
                name="audio"
                id="inputAudio"
                accept="audio/*"
                class="form-control">
            @error('audio_path')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="inputEbook" class="form-label"><strong>eBook (PDF):</strong></label>
            <input
                type="file"
                name="ebook"
                id="inputEbook"
                accept="application/pdf"
                class="form-control">
            @error('ebook_path')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>


        <button type="submit" class="btn btn-success" onclick="createBook(event)"><i class="fa-solid fa-floppy-disk"></i> Submit</button>

    </div>
</div>
@endsection

@section('scripts')

<script>

    function createBook(event) {

        console.log("click");

        event.preventDefault();

        const formData = new FormData();
            // Adicionar os dados do formulário ao FormData
            formData.append('name', $('#inputName').val());
            formData.append('detail', $('#inputDetail').val());
            formData.append('synopsis', $('#inputSynopsis').val());
            formData.append('price', $('#inputPrice').val());
            formData.append('price_ebook', $('#inputPriceEbook').val());
            formData.append('price_audio', $('#inputPriceAudio').val());
            formData.append('stock', $('#inputStock').val());
            formData.append('condition', $('#condition').val());
            formData.append('is_special_edition', $('#is_special_edition').is(':checked') ? 1 : 0);
            formData.append('author', $('#inputAuthor').val());

        // Adicionar a imagem ao FormData
        const files = $('#inputImage')[0].files;
            for (var i = 0; i < files.length; i++) {
                formData.append('image[]', files[i]); // Adicionando múltiplas imagens (se houver)
            }

        // Adicionar o arquivo de áudio ao FormData
        const fileAudio = $('#inputAudio')[0].files;
            for (var i = 0; i < fileAudio.length; i++) {
                formData.append('audio_path[]', fileAudio[i]); // Adicionando o arquivo de áudio
            }

        // Adicionar o arquivo de eBook ao FormData
        const fileEbook = $('#inputEbook')[0].files;
            for (var i = 0; i < fileEbook.length; i++) {
                formData.append('ebook_path[]', fileEbook[i]); // Adicionando o arquivo de eBook
            }

        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('books.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Book added successfully');
                    setTimeout(function () {
                        window.location.href = "{{ route('books.index') }}";
                    }, 1500);
                } else {
                    toastr.error('Error on add Book: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            toastr.error(errors[field][0]);
                        }
                    }
                } else {
                    toastr.error('Unexpected error: ' + error);
                }
            }
        });
    }

</script>
@endsection
