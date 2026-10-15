@extends('layouts.default')

@section('title', $title)
@section('classe-body', 'bg-body-tertiary')

@section('content')
    <main class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Usuários</h1>
                <p class="text-body-secondary mb-0">Gerencie os usuários cadastrados.</p>
            </div>
            <a href="{{ url('/register') }}" class="btn btn-primary">Novo usuário</a>
        </div>

        <section class="card shadow-sm">
            <div class="card-header">
                <h2 class="h5 mb-0">Lista de usuários</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 user-list-table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col" class="text-end">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-end">
                                    <a href="{{ url('/admin/user/'.$user->id) }}" class="btn btn-sm btn-outline-primary text-nowrap">
                                        Abrir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-body-secondary py-4">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>
    </main>
@endsection
