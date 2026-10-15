@extends('layouts.default')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="/"><b>Admin</b>LTE</a>
        </h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Esqueceu sua senha? Informe seu e-mail e a nova senha.
                </p>

                <form action="{{ route('users.password.update') }}" method="post">
                    @csrf
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="error">{{ $error }}</div>
                        @endforeach
                    @endif

                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <label class="visually-hidden" for="password">Nova senha</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Nova senha" />
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
            </div>
        </div>
    </main>
@endsection
