@extends('layouts.default')
@section('title', 'Redefinir senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ url('/') }}"><b>Bem vindo</b></a>
        </h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Informe sua nova senha.</p>

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                @endforeach

                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <label class="visually-hidden" for="email">E-mail</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control"
                            placeholder="E-mail" value="{{ old('email', $email) }}" required />
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>

                    <label class="visually-hidden" for="password">Nova senha</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control"
                            placeholder="Nova senha" required />
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>

                    <label class="visually-hidden" for="password_confirmation">Confirmar senha</label>
                    <div class="input-group mb-3">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control" placeholder="Confirmar senha" required />
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Redefinir senha</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection