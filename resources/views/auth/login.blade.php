<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rede Pistas</title>
    
    <!-- CSS Oficial AdminLTE 4 via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css" crossorigin="anonymous">
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}" class="text-decoration-none"><b>Rede</b>Pistas</a>
        </div>
        
        <div class="card shadow">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Entre com seu email e senha</p>

                <!-- Formulário apontando para a rota de login -->
                <form action="{{ route('login.store') }}" method="POST">

                    @csrf
                    
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        
                    </div>
                    
                    <div class="input-group mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Senha" required>
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>

                <p class="mb-0 mt-3 text-center">
                    Ainda não tem uma conta? <a href="{{ route('register') }}" class="text-center text-decoration-none">Cadastre-se</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
