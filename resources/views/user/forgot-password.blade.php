@extends('layouts.default')

@section('title', 'Trocar Senha')
@section('classe-body', 'login-page bg-body-secondary')

@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ route('admin.users.index') }}"><b>Trocar</b> senha</a>
        </h1>

        <div class="card card-outline card-primary">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Esqueceu sua senha? Informe seu email e a nova senha.</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
                        <input id="password" name="password" type="password" class="form-control" placeholder="Nova senha" minlength="8" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Trocar senha</button>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="{{ url('register') }}">Criar uma conta</a>
                </p>
            </div>
        </div>
    </main>
@endsection
