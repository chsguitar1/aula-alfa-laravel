@extends('layouts.default')

@section('title', 'Definir nova senha')
@section('classe-body', 'login-page bg-body-secondary')

@section('content')
    <main class="login-box">
        <h1 class="login-logo"><b>Nova senha</b></h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Digite seu e-mail e escolha uma nova senha.</p>

                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <label for="email" class="form-label">E-mail</label>
                    <input id="email" name="email" type="email"
                        class="form-control mb-3 @error('email') is-invalid @enderror"
                        value="{{ old('email', $email) }}" required autocomplete="email">
                    @error('email')
                        <div class="text-danger mb-3" role="alert">{{ $message }}</div>
                    @enderror

                    <label for="password" class="form-label">Nova senha</label>
                    <input id="password" name="password" type="password"
                        class="form-control mb-3 @error('password') is-invalid @enderror"
                        required minlength="8" autocomplete="new-password">
                    @error('password')
                        <div class="text-danger mb-3" role="alert">{{ $message }}</div>
                    @enderror

                    <label for="password_confirmation" class="form-label">Confirme a nova senha</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="form-control mb-3" required minlength="8" autocomplete="new-password">

                    <button type="submit" class="btn btn-primary w-100">Alterar senha</button>
                </form>
            </div>
        </div>
    </main>
@endsection
