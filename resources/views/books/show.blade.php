@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Show Book</h2>
    <div class="card-body">

        <div class="row">
            <!-- Botao de admin para voltar ao CRUD -->
            <div class="gap-10 d-md-flex justify-content-md-between">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-home"></i> Back</a>
                @endif

                <!-- Botao de voltar ao index da loja (aparece para admins e users) -->
                <a href="{{ url('/index_cart') }}" class="btn btn-warning btn-sm"><i class="fa fa-angle-left"></i> Go Shopping</a>
            </div>
        </div>

        <!-- Detalhes do item selecionado -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Name:</strong> <br/>
                    {{ $books->name }}
                </div>
            </div>
            <div class="col-xs-12- ol-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Details:</strong> <br/>
                    {{ $books->detail }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Author:</strong> <br/>
                    {{ $books->author }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Image:</strong ><br/> <br/>
                    @php
                    $images = json_decode($books->image, true);
                    @endphp

                    @if (!empty($images[0]))
                        <img src="{{ asset('images/' . $images[0]) }}" width="250px">
                    @else
                        <span>No image</span>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Synopsis:</strong> <br/>
                    {{ $books->synopsis }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Stock:</strong> <br/>
                    {{ $books->stock }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Condition:</strong> <br/>
                    {{ $books->condition }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Special Edition:</strong> <br/>
                    {{ $books->is_special_edition }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Price:</strong> <br/>
                    {{ $books->price }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Price (Ebook):</strong> <br/>
                    {{ $books->price_ebook }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Price (AudioBook):</strong> <br/>
                    {{ $books->price_audio }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Audiobook (MP3):</strong> <br/>
                    {{ $books->audio_path }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>eBook (PDF):</strong> <br/>
                    {{ $books->ebook_path }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
