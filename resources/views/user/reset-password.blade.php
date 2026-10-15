@extends('layouts.default')
@section('title', 'Redefinir senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
<main class="login-box">
    <div class="login-logo"><b>Laravel</b> | Senha</div>
    <div class="card">
        <div class="card-body login-card-body">
            <h1 class="h5 text-center mb-3">Redefinir senha</h1>
            <p class="login-box-msg">Informe e confirme sua nova senha.</p>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

            <form action="{{ route('password.update') }}" method="post">
                @csrf
            <label for="email" class="form-label">E-mail cadastrado</label>
            <div class="input-group mb-3">
                <input id="email" name="email" type="email" class="form-control" placeholder="E-mail" value="{{ old('email', $email ?? '') }}" required autocomplete="email" autofocus>
                <span class="input-group-text" aria-hidden="true">@</span>
            </div>
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="password" class="form-label">Nova senha</label>
            <input id="password" name="password" type="password" class="form-control mb-3" placeholder="No mínimo 8 caracteres" minlength="8" required autocomplete="new-password">
            <label for="password_confirmation" class="form-label">Confirme a nova senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control mb-3" minlength="8" required autocomplete="new-password">

                <div class="d-grid"><button type="submit" class="btn btn-primary">Alterar senha</button></div>
            </form>
            <p class="mt-3 mb-0"><a href="{{ url('/register') }}">Cadastrar usuário</a></p>
            <p class="mt-2 mb-0"><a href="{{ route('password.request') }}">Solicitar outro link</a></p>
        </div>
    </div>
</main>
@endsection
