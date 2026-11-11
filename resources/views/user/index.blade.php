@extends('layouts.default')

@section('title', 'Listar Usuários | Rede Patas')

@section('content')
<div class="mt-2">
    <table class="table table-dark table-bordered align-middle">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Nome</th>
                <th>Email</th>
                <th style="width: 220px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}.</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm me-1">Editar</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este usuário?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
