@extends('layouts.default')
@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ url('/') }}"><b>Recuperar</b> senha</a>
        </h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Esqueceu sua senha? Informe seu email e cadastre uma nova senha.</p>

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

                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password">Nova senha</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Nova senha" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password_confirmation">Confirmar nova senha</label>
                    <div class="input-group mb-3">
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Confirmar nova senha" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Alterar senha</button>
                            </div>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-0">
                    <a href="{{ url('register') }}" class="text-center">Cadastrar um novo usuário</a>
                </p>
            </div>
        </div>
    </main>
@endsection
