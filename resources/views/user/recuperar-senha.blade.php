 @extends('layouts.default')

@section('classe-body', 'register-page')

@section('content')
    <main class="register-box">
        <h1 class="register-logo">
            <a href="/"><b>Recuperar senha</b></a>
        </h1>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">
                    Preencha os campos para alterar sua senha
                </p>

                <form action="{{ route('senha.alterar') }}" method="post">
                    @csrf

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="error">{{ $error }}</div>
                        @endforeach
                    @endif

                    @if (session('sucesso'))
                        <div class="alert alert-success">
                            {{ session('sucesso') }}
                        </div>
                    @endif

                    <div class="input-group mb-3">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-control"
                            placeholder="Email cadastrado"
                            value="{{ old('email') }}"
                            required
                        >
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control"
                            placeholder="Nova senha"
                            required
                        >
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="form-control"
                            placeholder="Confirme a nova senha"
                            required
                        >
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-6">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Alterar senha
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection