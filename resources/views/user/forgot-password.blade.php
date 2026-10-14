@extends('layouts.default')

@section('content')
<div class="login-page bg-body-secondary min-vh-100 d-flex flex-column justify-content-center align-items-center">
  <main class="login-box">
    <h1 class="login-logo text-center mb-4">
      <a href="#"><b>Admin</b>LTE</a>
    </h1>
    
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">
          Esqueceu sua senha? Digite seu e-mail e defina uma nova senha abaixo.
        </p>

        <!-- Exibição de mensagens de erro ou sucesso -->
        @if(session('success'))
          <div class="alert alert-success" role="alert">
            {{ session('success') }}
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Formulário apontando para a rota POST que criaremos no UserController -->
        <form action="{{ route('user.password.update') }}" method="POST">
          @csrf

          <!-- Campo de E-mail -->
          <label class="visually-hidden" for="forgotEmail">Email</label>
          <div class="input-group mb-3">
            <input id="forgotEmail" type="email" name="email" class="form-control" placeholder="E-mail cadastrado" required />
            <div class="input-group-text">
              <span class="bi bi-envelope"></span>
            </div>
          </div>

          <!-- Campo de Nova Senha (Adicionado para atender à lógica de alteração) -->
          <label class="visually-hidden" for="forgotPassword">Nova Senha</label>
          <div class="input-group mb-3">
            <input id="forgotPassword" type="password" name="password" class="form-control" placeholder="Nova Senha" required />
            <div class="input-group-text">
              <span class="bi bi-lock"></span>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Alterar senha</button>
              </div>
            </div>
          </div>
        </form>

        <p class="mt-3 mb-1">
          <a href="{{ route('login') }}">Fazer login</a>
        </p>
      </div>
    </div>
  </main>
</div>
@endsection