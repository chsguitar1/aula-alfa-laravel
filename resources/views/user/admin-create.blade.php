@extends('layouts.default')

@section('title', 'Novo usuário')

@section('content')
    <div class="card" style="max-width: 560px;">
        <div class="card-header">Cadastrar novo usuário</div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="name">Nome</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Nome" required autofocus />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Email" required />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Senha</label>
                    <input id="password" name="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Senha (mínimo 8 caracteres)"
                        required />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
