@extends('layouts.default')
@section('classe-body', 'login-page bg-body-secondary')
@section('content')
    <main class="login-box">
        <h1 class="login-logo">
            <a href="#"><b>Recuperar</b> Senha</a>
        </h1>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Você esqueceu sua senha? Aqui você pode facilmente definir uma nova senha.
                </p>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @endif

                <form action="{{ route('users.password.reset') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password">Nova Senha</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Nova Senha" />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password_confirmation">Confirmar Nova Senha</label>
                    <div class="input-group mb-3">
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Confirmar Nova Senha" />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Alterar Senha</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('admin.users.index') }}">Voltar</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </main>
    <!-- /.login-box -->
@endsection
