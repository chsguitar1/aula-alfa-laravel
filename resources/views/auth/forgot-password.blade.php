
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>

    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(50, 100, 150, 0.13);
            overflow: hidden;
        }

        .card-body {
            padding: 35px;
        }

        .lock-icon {
            width: 65px;
            height: 65px;
            background: #e3f2fd;
            color: #2196f3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 18px;
        }

        h3 {
            color: #24577a;
            font-weight: bold;
        }

        .login-box-msg {
            color: #78909c;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 9px;
            border: 1px solid #d6e8f5;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #64b5f6;
            box-shadow: 0 0 0 3px rgba(100, 181, 246, 0.15);
        }

        .btn-primary {
            background: #42a5f5;
            border: none;
            border-radius: 9px;
            padding: 12px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: #1e88e5;
        }

        .back-link {
            color: #42a5f5;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            color: #1565c0;
        }
    </style>
</head>

<body>

<div class="login-box">
    <div class="card">
        <div class="card-body text-center">

            <div class="lock-icon">
                &#128274;
            </div>

            <h3>Redefinir senha</h3>

            <p class="login-box-msg">
                Informe seu e-mail e escolha uma nova senha.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger text-start">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Seu e-mail"
                           value="{{ old('email') }}"
                           required>
                </div>

                <div class="mb-3">
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Nova senha"
                           required>
                </div>

                <div class="mb-4">
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Confirme sua nova senha"
                           required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Alterar senha
                </button>
            </form>

            <div class="mt-4">
                <a href="{{ url('/') }}" class="back-link">
                    &#8592; Voltar para a página inicial
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
