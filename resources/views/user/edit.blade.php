@extends('layouts.default')

@section('title', 'Editar Usuário | Rede Patas')

@section('content')
<div class="card bg-dark border-secondary mt-2" style="max-width: 600px;">
    <div class="card-header border-secondary">
        <h5 class="card-title mb-0">Editar Usuário #{{ $user->id }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}"
                    required
                />
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}"
                    required
                />
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nova Senha (opcional)</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Deixe em branco para manter a senha atual"
                />
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>
</div>
@endsection
