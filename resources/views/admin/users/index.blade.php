@extends('layouts.default')

@section('content')
<div class="container mt-4">
    <div class="card bg-dark text-white shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Listar usuário</h3>
        </div>
        <div class="card-body">
            <table class="table table-dark table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th style="width: 150px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Paginação -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection