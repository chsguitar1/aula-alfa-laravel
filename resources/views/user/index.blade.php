@extends('layouts.default')

@section('classe-body', 'register-page bg-body-secondary')

@section('content')

@if (session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger" role="alert">
    @foreach ($errors->all() as $error)
    <p class="mb-0">{{ $error }}</p>
    @endforeach
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Usuários cadastrados</h3>

    <a href="{{ route('admin.user.create') }}" class="btn btn-success">
        <i class="bi bi-person-plus"></i>
        Novo cadastro
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <td>
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-sm">
                        Editar
                    </a>

                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Deseja realmente excluir este usuário?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">
                    Nenhum usuário cadastrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $users->links('pagination::bootstrap-5') }}

@endsection