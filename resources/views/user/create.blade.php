@extends('layouts.default')

@section('title', 'Cadastro de Usuário')
@section('classe-body', 'register-page bg-body-secondary')

@section('content')
    <main class="register-box">
        <h1 class="register-logo">
            <a href="{{ route('admin.users.index') }}"><b>Bem vindo</b></a>
        </h1>

        <div class="card card-outline card-primary">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Preencha os campos para se registrar</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="name">Nome</label>
                    <div class="input-group mb-3">
                        <input id="name" name="name" type="text" class="form-control" placeholder="Nome" value="{{ old('name') }}" />
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="password">Senha</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Senha" />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>

                <p class="mb-0 mt-3 text-center">
                    <a href="{{ route('admin.users.index') }}">Ver usuários cadastrados</a>
                </p>
                
                <p class="mb-0 mt-3 text-center">
                    <a href="{{ route('password.forgot') }}">Esqueci minha senha</a>
                </p>
            </div>
        </div>
    </main>
@endsection
