@extends('layouts.default')
@section('title', 'Recuperar senha')

@section('classe-body', 'login-page bg-body-secondary')

@section('content')
    <main class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Informe seu e-mail para receber o link de redefinição da senha.</p>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif

                <form action="{{ route('users.password-email') }}" method="post">
                    @csrf
                    <label class="visually-hidden" for="email">E-mail</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="E-mail" value="{{ old('email') }}" required>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <div class="text-danger mb-3">{{ $message }}</div>
                    @enderror
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Solicitar nova senha</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection