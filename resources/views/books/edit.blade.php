@extends('books.layout')

<style>
    .custom-file-upload {
        display: inline-block;
        padding: 8px 16px;
        cursor: pointer;
        background-color: #198754; /* verde Bootstrap */
        color: #fff;
        border-radius: 5px;
        margin-top: 5px;
        transition: 0.3s ease;
    }

    .custom-file-upload:hover {
        background-color: #146c43;
    }

    #inputImage {
        display: none;
    }

    .image-preview {
        display: block;
        margin-top: 10px;
        max-width: 300px;
        max-height: 200px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Edit Book</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->
        <!--Botao para voltar ao CRUD -->
        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <!-- Form para editar o livro selecionado -->

        <form id="editBookForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="inputName" class="form-label"><strong>Name:</strong></label>
                <input
                    type="text"
                    name="name"
                    value="{{ $books->name }}"
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
                    class="form-control @error('detail') is-invalid @enderror"
                    style="height:150px"
                    name="detail"
                    id="inputDetail"
                    placeholder="Detail">{{ $books->detail }}</textarea>
                @error('detail')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputSynopsis" class="form-label"><strong>Synopsis:</strong></label>
                <textarea
                    class="form-control @error('synopsis') is-invalid @enderror"
                    style="height:150px"
                    name="synopsis"
                    id="inputSynopsis"
                    placeholder="Synopsis">{{ $books->synopsis }}</textarea>
                @error('synopsis')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Imagem atual:</strong></label>

                @php
                    $images = json_decode($books->image, true);
                @endphp

                @if (!empty($images[0]))
                    <img src="{{ asset('images/' . $images[0]) }}" alt="Imagem do livro" class="image-preview">
                @else
                    <p><em>Sem imagem disponível.</em></p>
                @endif

                <br>

                <!-- Botão customizado para upload -->
                <label for="inputImage" class="custom-file-upload mt-2">
                    <i class="fa fa-upload"></i> Selecionar nova imagem
                </label>
                <input
                    type="file"
                    name="image"
                    id="inputImage"
                    accept="image/jpeg, image/png, image/jpg, image/gif, image/webp"
                    class="@error('image') is-invalid @enderror">
                @error('image')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Áudio atual:</strong></label>

                @php
                    $audio_path = json_decode($books->audio_path, true);
                @endphp

                @if (!empty($audio_path) && !empty($audio_path[0]))
                    <audio controls>
                        <source src="{{ asset('audios/' . $audio_path[0]) }}" type="audio/mpeg">
                        Seu navegador não suporta o elemento de áudio.
                    </audio>
                @else
                    <p><em>Sem áudio disponível.</em></p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Ebook atual:</strong></label>

                @php
                    $ebook_path = json_decode($books->ebook_path, true);
                @endphp

                @if (!empty($ebook_path) && !empty($ebook_path[0]))
                    <a href="{{ asset('ebooks/' . $ebook_path[0]) }}" target="_blank" class="btn btn-sm btn-outline-info">Ver Ebook</a>
                @else
                    <p><em>Sem ebook disponível.</em></p>
                @endif
            </div>


            <div class="mb-3">
                <label for="inputStock" class="form-label"><strong>Stock:</strong></label>
                <input
                    type="number"
                    name="stock"
                    step="0.01"
                    value="{{ $books->stock }}"
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
                        <option value="New" {{ $books->condition == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Used" {{ $books->condition == 'Used' ? 'selected' : '' }}>Used</option>
                        <option value="Limited Edition" {{ $books->condition == 'Limited Edition' ? 'selected' : '' }}>Limited Edition</option>
                        <option value="Signed" {{ $books->condition == 'Signed' ? 'selected' : '' }}>Signed</option>
                    </select>
                @error('condition')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <input type="hidden" name="is_special_edition" value="0">
                <label for="is_special_edition" class="form-label">
                    <input
                        type="checkbox"
                        name="is_special_edition"
                        id="is_special_edition"
                        class="form-check-input"
                        value="1"
                        {{ old('is_special_edition', $books->is_special_edition ?? false) ? 'checked' : '' }}>Is Special Edition?
                </label>
            </div>

            <div class="mb-3">
                <label for="inputPrice" class="form-label"><strong>Price:</strong></label>
                <input
                    type="number"
                    name="price"
                    step="0.01"
                    value="{{ $books->price }}"
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
                    value="{{ $books->price_ebook }}"
                    class="form-control @error('price_ebook') is-invalid @enderror"
                    id="inputPrice"
                    placeholder="Ex: 29.90">
                @error('price_ebook')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputPriceAudio" class="form-label"><strong>Price (Audio):</strong></label>
                <input
                    type="number"
                    name="price_audio"
                    step="0.01"
                    value="{{ $books->price_audio }}"
                    class="form-control @error('price_audio') is-invalid @enderror"
                    id="inputPrice"
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
                    value="{{ $books->author }}"
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
                    class="form-control"
                    value="{{ $books->audio_path }}">
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
                    class="form-control"
                    value="{{ $books->ebook_path }}">
                @error('ebook_path')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update</button>
        </form>

    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        $('#editBookForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            formData.append('_method', 'PUT');

            $.ajax({
                url: "{{ route('books.update', $books->id) }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    toastr.success('Book updated successfully.');
                    setTimeout(function () {
                        window.location.href = "{{ route('books.index') }}";
                    }, 1500);
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        toastr.error('Error on update the book: ' + xhr.responseText);
                    }
                }
            });
        });
    });
</script>
@endsection
