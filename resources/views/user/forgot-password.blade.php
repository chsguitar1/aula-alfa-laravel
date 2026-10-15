@extends('layouts.default')
@section('title', 'Recuperar senha')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ url('/') }}"><b>Bem vindo</b></a>
        </h1>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Esqueceu sua senha? Informe seu email e escolha uma nova senha.</p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="error text-danger">{{ $error }}</div>
                    @endforeach
                @endif

                <form action="{{ route('users.password.update') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password">Nova senha</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control"
                            placeholder="Nova senha" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password_confirmation">Confirmar nova senha</label>
                    <div class="input-group mb-3">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control" placeholder="Confirmar nova senha" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock"></span>
                        </div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Alterar senha</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ url('register') }}">Criar uma nova conta</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </main>
@endsection
