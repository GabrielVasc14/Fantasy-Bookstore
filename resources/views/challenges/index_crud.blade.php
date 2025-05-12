@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Livraria Fantasy - ADMIN - Challenges</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->

        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <!-- Botao para voltar ao CRUD -->
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!-- Botao para criar challenges -->
            <a href="{{ route('challenges.create') }}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> Add New Challenge</a>
        </div>

        <!-- Tabela do crud -->
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">Number</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Target</th>
                    <th>Period</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($challenges as $challenge)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $challenge->title }}</td>
                        <td>{{ $challenge->description }}</td>
                        <td>{{ $challenge->type }}</td>
                        <td>{{ $challenge->target }}</td>
                        <td>{{ $challenge->period }}</td>
                        <td>
                            <!-- Form para deletar, editar e mostrar cupons -->
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-info btn-sm md-2"><i class="fa-solid fa-list"></i></a>

                            <a href="{{ route('challenges.edit', $challenge->id) }}" class="btn btn-primary btn-sm md-2"><i class="fa-solid fa-pen-to-square"></i></a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-id="{{ $challenge->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">There are no data.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        {!! $challenges->withQueryString()->links('pagination::bootstrap-5') !!}

    </div>
</div>

@endsection

@section('scripts')
<script>
    //Deletar Challenge
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const challengeId = this.getAttribute('data-id');
                const csrfToken = '{{ csrf_token() }}';
                const clickedButton = this;

                if(!challengeId) {
                    toastr.error('Challenge ID not found.');
                    return;
                }

                Swal.fire({
                    title: 'Delete challenge?',
                    text: 'Are you sure you want to delete this challenge?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if(result.isConfirmed) {
                        fetch(`/challenges/destroy/${challengeId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                _method: 'DELETE',
                            })
                        })
                        .then(response => {
                            if(response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Challenge has been deleted.',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                setTimeout(() => window.location.reload(), 1600);
                            } else {
                                toastr.error('Error deleting challenge.');
                            }
                        })
                        .catch(error =>{
                            console.error('Error:', error);
                            toastr.error('Internal error: Unable to delete challenge.');
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
