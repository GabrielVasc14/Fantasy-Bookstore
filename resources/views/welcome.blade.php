@extends('layouts.app')

@section('content')
    <div class="container mx-auto text-center py-20">
        <h1 class="text-5x1 font-extrabold text-gray-800 mb-4">Bem Vindo à <span class="text-blue-500">Livraria Fantasy</span></h1>
        <p class="text-xl text-gray-600 mb-8">Sua livraria online com os melhores livros de fantasia, aventura e muito mais!</p>

        <!-- imagem de destaque -->
        <img src="{{ asset('images/fantasy.png') }}" alt="Livraria Fantasy" class="w-min max-w-3xl mx-auto mb-8 rounded-lg shadow-lg">

        <!-- Botao de chamada -->
        <div>
            <a href="{{ url('/index_cart') }}" class="bg-blue-500 text-white py-3 px-6 rounded-lg text-lg hover:bg-blue-600 transition-all">Veja nossos livros!!</a>
        </div>

        <p class="mt-6 text-lg text-gray-700">
            <strong>Explore um universo magico de historias!</strong>
        </p>
    </div>
@endsection
