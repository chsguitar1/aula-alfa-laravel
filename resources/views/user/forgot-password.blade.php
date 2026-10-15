@extends('layouts.default')
@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
<main class="login-box">
    <div class="login-logo"><b>Laravel</b> | Senha</div>
    <div class="card">
        <div class="card-body login-card-body">
            <h1 class="h5 text-center mb-3">Recuperar senha</h1>
            <p class="login-box-msg">Esqueceu sua senha? Informe seu e-mail para receber o link de recuperação.</p>
    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

            <form action="{{ route('password.email') }}" method="post">
                @csrf
            <label for="email" class="form-label">E-mail cadastrado</label>
            <div class="input-group mb-3">
                <input id="email" name="email" type="email" class="form-control" placeholder="E-mail" value="{{ old('email', $email ?? '') }}" required autocomplete="email" autofocus>
                <span class="input-group-text" aria-hidden="true">@</span>
            </div>

                <div class="d-grid"><button type="submit" class="btn btn-primary">Enviar link de recuperação</button></div>
            </form>
            <p class="mt-3 mb-0"><a href="{{ url('/register') }}">Cadastrar usuário</a></p>

        </div>
    </div>
</main>
@endsection
