@extends('layouts.password')

@section('title', 'Alterar senha')

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Informe sua senha atual e escolha uma nova senha.</p>

            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif

            <form action="{{ route('password.update') }}" method="post">
                @csrf

                <label class="visually-hidden" for="email">E-mail</label>
                <div class="input-group mb-3">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="E-mail"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                    >
                    <span class="input-group-text" aria-hidden="true">@</span>
                </div>
                @error('email')
                    <p class="text-danger small" role="alert">{{ $message }}</p>
                @enderror

                <label class="visually-hidden" for="current_password">Senha atual</label>
                <div class="input-group mb-3">
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        placeholder="Senha atual"
                        autocomplete="current-password"
                        required
                    >
                </div>
                @error('current_password')
                    <p class="text-danger small" role="alert">{{ $message }}</p>
                @enderror

                <label class="visually-hidden" for="password">Nova senha</label>
                <div class="input-group mb-3">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Nova senha"
                        autocomplete="new-password"
                        required
                    >
                </div>
                @error('password')
                    <p class="text-danger small" role="alert">{{ $message }}</p>
                @enderror

                <label class="visually-hidden" for="password_confirmation">Confirme a nova senha</label>
                <div class="input-group mb-3">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="form-control"
                        placeholder="Confirme a nova senha"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Alterar senha</button>
                </div>
            </form>

            <p class="mt-3 mb-0">
                <a href="{{ route('users.create') }}">Voltar ao cadastro</a>
            </p>
        </div>
    </div>
@endsection
