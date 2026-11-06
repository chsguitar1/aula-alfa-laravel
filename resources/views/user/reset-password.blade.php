@extends('layouts.default')
@section('title', 'Definir nova senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
<main class="login-box">
    <div class="login-logo"><b>Nova senha</b></div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Escolha uma nova senha para sua conta.</p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('users.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" placeholder="E-mail" aria-label="E-mail" autocomplete="email" required>
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Nova senha" aria-label="Nova senha" autocomplete="new-password" minlength="8" required>
                    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar nova senha" aria-label="Confirmar nova senha" autocomplete="new-password" minlength="8" required>
                    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Alterar senha</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
