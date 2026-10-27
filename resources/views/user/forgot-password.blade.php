@extends('layouts.default')
@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
<main class="login-box">
    <div class="login-logo"><b>Recuperar senha</b></div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Informe seu e-mail para receber um link de recuperação de senha.</p>
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('users.password.email') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" value="{{ old('email') }}" aria-label="E-mail" autocomplete="email" required>
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Enviar link de recuperação</button>
                </div>
            </form>
            <p class="mt-3 mb-0"><a href="{{ url('/register') }}">Voltar ao cadastro</a></p>
        </div>
    </div>
</main>
@endsection
