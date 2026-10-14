<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Recuperar Palavra-passe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
  </head>
  
  <body class="login-page bg-body-secondary">
    <main class="login-box">
      <h1 class="login-logo">
        <a href="#"><b>Admin</b>LTE</a>
      </h1>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">
            Esqueceu-se da sua palavra-passe? Defina uma nova abaixo.
          </p>

          <form action="{{ route('password.update') }}" method="post">
            @csrf
            
            <label class="visually-hidden" for="forgotEmail">Email</label>
            <div class="input-group mb-3">
              <input id="forgotEmail" name="email" type="email" class="form-control" placeholder="Email" required />
              <div class="input-group-text">
                <span class="bi bi-envelope"></span>
              </div>
            </div>

            <label class="visually-hidden" for="password">Nova Palavra-passe</label>
            <div class="input-group mb-3">
              <input id="password" name="password" type="password" class="form-control" placeholder="Nova Palavra-passe" required />
              <div class="input-group-text">
                <span class="bi bi-lock-fill"></span>
              </div>
            </div>

            <label class="visually-hidden" for="password_confirmation">Confirmar Palavra-passe</label>
            <div class="input-group mb-3">
              <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Confirmar Palavra-passe" required />
              <div class="input-group-text">
                <span class="bi bi-lock-fill"></span>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">Alterar palavra-passe</button>
                </div>
              </div>
            </div>
          </form>

          <p class="mt-3 mb-1">
            <a href="/login">Voltar ao Login</a>
          </p>
        </div>
      </div>
    </main>
  </body>
</html>
