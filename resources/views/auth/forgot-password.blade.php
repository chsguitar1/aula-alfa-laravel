@extends('layouts.default')
@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ url('/') }}"><b>Bem vindo</b></a>
        </h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Esqueceu sua senha? Informe seu e-mail para receber o link de recuperação.
                </p>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                @endforeach

                <form action="{{ route('password.email') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="email">E-mail</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control"
                            placeholder="E-mail" value="{{ old('email') }}" required />
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Solicitar nova senha</button>
                    </div>
                </form>

                <p class="mt-3 mb-1"><a href="{{ url('/') }}">Entrar</a></p>
                <p class="mb-0"><a href="{{ url('register') }}">Cadastrar-se</a></p>
            </div>
        </div>
    </main>
@endsection