<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    @vite('resources/scss/app.scss')
</head>
<body class="register-page bg-body-secondary">
@vite('resources/js/app.js')
<main class="register-box">
    <h1 class="register-logo"><b>Cadastro</b></h1>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="register-box-msg">Preencha os campos para se registrar</p>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger py-1">{{ $error }}</div>
                @endforeach
            @endif

            <form action="{{ route('users.store') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input name="name" type="text" class="form-control" placeholder="Nome" value="{{ old('name') }}" />
                    <div class="input-group-text"><span class="bi bi-person"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" />
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Senha" />
                    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>

            <p class="mt-3 mb-0"><a href="{{ url('/login') }}">Voltar ao login</a></p>
        </div>
    </div>
</main>
</body>
</html>
