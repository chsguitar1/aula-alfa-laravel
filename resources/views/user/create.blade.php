@extends('layouts.default')

@section('classe-body', 'register-page bg-body-secondary')

@section('content')
    <main class="register-box">
        <h1 class="register-logo">
            <a href="{{ route('login') }}"><b>Bem-vindo</b></a>
        </h1>
        
        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Preencha os campos para se registrar</p>

                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3 p-2">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <label class="visually-hidden" for="name">Nome</label>
                    <div class="input-group mb-3">
                        <input id="name" name="name" type="text" class="form-control" placeholder="Nome" value="{{ old('name') }}" required autofocus />
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                    </div>

                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <label class="visually-hidden" for="password">Password</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password" required />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-8">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">Já tem uma conta? Entrar</a>
                </div>
            </div>
        </div>
    </main>
@endsection