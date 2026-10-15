@extends('layouts.default')

@section('classe-body', 'login-page bg-body-secondary')

@section('content')
    <main class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Defina uma nova senha para sua conta.</p>

                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label" for="email">E-mail</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $email) }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Nova senha</label>
                        <input id="password" name="password" type="password" class="form-control" required minlength="8">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirme a nova senha</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required minlength="8">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Alterar senha</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection