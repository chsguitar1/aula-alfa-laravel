<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="login-page">
    <div class="login-container">
        <h1>Bem-vindo de volta</h1>
        <p>Entre com suas credenciais</p>
        @if (session('sucess'))
            <p class="sucess-message">
                {{ session('sucess') }}
            </p>
        @endif
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">E-mail</label>
            @error('email')
            <p class="error-message">{{ $message }}</p>
            @enderror
            <input type="email" id="email" name=email required>
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Entrar</button>
            <div class="register-link">
                <p>
                    Ainda nao possui cadastro?
                    <a href="{{ url('/register') }}">Cadastre-se</a>
                </p>
            </div>
        </form>
    </div>

</body>
</html>
