@extends('layouts.default')

@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')

@section('content')
    <main class="login-box">
        <h1 class="login-logo"><b>Recuperar senha</b></h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Informe seu e-mail para receber um link de redefinição de senha.</p>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif

                <form action="{{ route('password.email') }}" method="post">
                    @csrf
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus autocomplete="email">
                        <span class="input-group-text"><span class="bi bi-envelope"></span></span>
                    </div>
                    @error('email')
                        <div class="text-danger mb-3" role="alert">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary w-100">Enviar link de recuperação</button>
                </form>
            </div>
        </div>
    </main>
@endsection
