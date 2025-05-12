@extends('books.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Livraria Fantasy - ADMIN - Rewards</h2>
    <div class="card-body">

        <!-- Apenas admins acessam essa pagina -->

        <div class="d-grip gap-2 d-md-flex justify-content-md-end">
            <!-- Botao para voltar ao CRUD -->
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-book"></i> CRUD - Books</a>
            <!-- Botao para criar rewards -->
            <a href="{{ route('rewards.create') }}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> Add New Reward</a>
        </div>

        <!-- Tabela do crud -->
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">Number</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Cost Points</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rewards as $reward)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $reward->name }}</td>
                        <td>{{ $reward->description }}</td>
                        <td>{{ $reward->cost_points }}</td>
                        <td>{{ $reward->type }}</td>
                        <td>{{ $reward->value }}</td>
                        <td>
                            <!-- Form para deletar, editar e mostrar cupons -->
                            <a href="{{ route('rewards.show', $reward->id) }}" class="btn btn-info btn-sm md-2"><i class="fa-solid fa-list"></i></a>

                            <a href="{{ route('rewards.edit', $reward->id) }}" class="btn btn-primary btn-sm md-2"><i class="fa-solid fa-pen-to-square"></i></a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm btn-delete" data-id="{{ $reward->id }}">
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

        {!! $rewards->withQueryString()->links('pagination::bootstrap-5') !!}

    </div>
</div>

@endsection

@section('scripts')
<script>
    // Deletar reward
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const rewardId = this.getAttribute('data-id');
                const csrfToken = '{{ csrf_token() }}';
                const clickedButton = this;

                if (!rewardId) {
                    toastr.error('Internal error: Reward ID not found.');
                    return;
                }

                Swal.fire({
                    title: 'Delete reward?',
                    text: 'Are you sure you want to delete this reward?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/rewards/destroy/${rewardId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Reward has been deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => window.location.reload(), 1600);
                            } else {
                                toastr.error('Failed to delete the reward.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error trying to delete the reward.');
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
