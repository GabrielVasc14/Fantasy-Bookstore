@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Perfil do Usuário</h2>
        <a href="{{ url('/index_cart') }}" class="btn btn-outline-info mt-2 mt-md-0">
            <i class="fa fa-angle-left"></i> Voltar
        </a>
    </div>

    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-warning text-white fw-bold">
            Details about your profile
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="banner" class="form-label">Banner (1200x300px)</label>
                    @if(auth()->user()->banner)
                        <img src="{{ asset('storage/' . auth()->user()->banner) }}" alt="Banner" class="d-block mb-2 w-100" style="height: 200px; object-fit: cover;">
                    @endif
                    <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                    @error('banner')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">Avatar</label>
                    <div class="d-flex align-items-center mb-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle me-3" style="width: 80px; height: 80px;">
                        @endif
                        <input type="file" class="form-control-file" id="avatar" name="avatar" accept="image/*">
                    </div>
                    @error('avatar')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                    @error('bio')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="favorite_books" class="form-control">Favorite Books</label>
                    <input type="text" name="favorite_books" id="favorite_books" class="form-control" value="{{ old('favorite_books', auth()->user()->favorite_books) }}">
                    @error('favorite_books')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning">Save</button>
            </form>
        </div>
    </div>

    <!-- Cupons usados -->
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-info text-white fw-bold">
            Cupons Usados
        </div>
        <div class="card-body">
            @foreach (auth()->user()->coupons as $coupon)
                <li>
                    Code: {{ $coupon->code }}<br>
                    Discount: € {{ number_format($coupon->pivot->discount, 2, ',', '.') }} <br>
                </li>
            @endforeach
        </div>
    </div>

    <!-- Sessão: Atualizar Informações -->
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-primary text-white fw-bold">
            Atualizar Informações do Perfil
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Sessão: Atualizar Senha -->
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-secondary text-white fw-bold">
            Alterar Senha
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Sessão: Excluir Conta -->
    <div class="card animate__animated animate__fadeIn">
        <div class="card-header bg-danger text-white fw-bold">
            Excluir Conta
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
