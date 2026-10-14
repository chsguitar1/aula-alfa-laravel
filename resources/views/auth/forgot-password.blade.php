@extends('layouts.default')

@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')

@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ url('/') }}"><b>Aula</b> Alfa</a>
        </h1>

        <section class="card" aria-labelledby="forgot-password-title">
            <div class="card-body login-card-body">
                <h2 id="forgot-password-title" class="login-box-msg fs-5 fw-semibold">Esqueceu sua senha?</h2>
                <p class="text-center text-body-secondary mb-4">
                    Informe seu e-mail para receber as instruções de recuperação.
                </p>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="post">
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
                        <div class="input-group-text" aria-hidden="true">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Solicitar nova senha</button>
                    </div>
                </form>

                <p class="mt-4 mb-0 text-center">
                    <a href="{{ url('/register') }}">Voltar ao cadastro</a>
                </p>
            </div>
        </section>
    </main>
@endsection
