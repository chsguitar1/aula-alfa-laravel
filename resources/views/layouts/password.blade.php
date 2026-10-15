<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Redefinição de senha')</title>
    <link rel="stylesheet" href="{{ \Illuminate\Support\Facades\Vite::asset('resources/scss/app.scss') }}">
</head>
<body class="login-page bg-body-secondary">
    <main class="login-box">
        <h1 class="login-logo">
            <a href="{{ route('home') }}"><b>Aula</b> Alfa</a>
        </h1>
        @yield('content')
    </main>
</body>
</html>
