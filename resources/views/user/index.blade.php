@extends('layouts.default')
@section('classe-body', 'register-page', 'register-page bg-body-secondary')
@section('content')


   
    <table class="table table-bordered" role="table">
        <thead>
            <tr>
                <th style="width: 10px" scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Ações</th>
            
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            
            <tr class="align-middle">
                <td>
                    {{ $user->id }}.</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                    {{-- <form action="{{ route('admin.user.destroy', $user->id) }} " method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE') --}}
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    {{-- </form> --}}
                </td>
            </tr>
            @endforeach
                
        </tbody>
    </table>
     {{ $users->links('pagination::bootstrap-5') }}
@endsection
