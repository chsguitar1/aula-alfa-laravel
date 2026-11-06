
@extends('layouts.default')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('classe-body', 'register-page')

@section('content')

<main class="register-box">

    <div class="register-card">

        <h1 class="register-title">
            {{ request()->routeIs('admin.users.create') ? 'Cadastrar usuário' : 'Criar conta' }}
        </h1>

        <p class="register-description">
            Preencha os campos abaixo para realizar o cadastro.
        </p>

        @if ($errors->any())
            <div class="register-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ request()->routeIs('admin.users.create') ? route('admin.users.store') : route('users.store') }}" method="POST">
            @csrf

            <label for="name">Nome completo</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="Digite seu nome completo"
                value="{{ old('name') }}"
                autocomplete="name"
                required
            >

            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="seu@email.com"
                value="{{ old('email') }}"
                autocomplete="email"
                required
            >

            <label for="password">Senha</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Mínimo de 8 caracteres"
                minlength="8"
                autocomplete="new-password"
                required
            >

            <button type="submit" class="register-submit">
                {{ request()->routeIs('admin.users.create') ? 'Cadastrar usuário' : 'Criar conta' }}
            </button>
        </form>

        @if (request()->routeIs('admin.users.create'))
            <p class="register-login">
                <a href="{{ route('admin.users.index') }}">
                    Voltar ao painel administrativo
                </a>
            </p>
        @else
            <p class="register-login">
                Já possui uma conta?
                <a href="{{ route('login') }}">Entrar</a>
            </p>
        @endif

    </div>

</main>

@endsection
