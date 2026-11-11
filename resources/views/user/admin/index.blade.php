@extends('layouts.default')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-dark text-light border-secondary shadow-sm">
            <div class="card-header border-secondary">
                <h3 class="card-title">Listar Usuários</h3>
            </div>
            
            <div class="card-body p-0">
                <table class="table table-dark table-striped table-hover m-0">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th style="width: 150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- O loop @foreach assume que o seu Controller envia a variável $users -->
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <!-- Botão Editar (Azul) -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                
                                <!-- Botão Excluir (Vermelho) com formulário de segurança -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection