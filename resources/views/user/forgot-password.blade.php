<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/scss/app.scss')
    <title>Recuperar senha</title>
</head>

<body style="font-family: Arial; max-width: 400px; margin: 80px auto;">

    <h1>Recuperar senha</h1>

    <p>Informe seu e-mail para solicitar a recuperação.</p>

    @if (session('sucesso'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('sucesso') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif

    @if ($errors->any())
        <p style="color: red">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <label for="email">E-mail:</label>

        <input type="email" id="email" name="email" required
            style="display: block; width: 100%; padding: 10px; margin: 10px 0;">

        <button type="submit">Recuperar senha</button>
    </form>

    <p><a href="/register">Voltar ao cadastro</a></p>

</body>

</html>
