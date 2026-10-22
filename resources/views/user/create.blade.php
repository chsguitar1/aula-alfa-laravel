@extends('layouts.default')
@section('classe-body', 'register-page', 'register-page bg-body-secondary')
@section('content')
    <main class="register-box">
        <h1 class="register-logo">
            <a href="../index2.html"><b>Bem vindo</b></a>
        </h1>
        <!-- /.register-logo -->
        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Preencha os campos para se registrar</p>

                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label class="visually-hidden" for="name">Nome</label>
                    <div class="input-group mb-3">
                        <input id="name" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Nome" />
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                    </div>
                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email" />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        <div class="invalid-feedback">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <label class="visually-hidden" for="password">Password</label>
                    <div class="input-group mb-3">
                        <input id="password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password" />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        <div class="invalid-feedback">
                            @error('password')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <!--begin::Row-->
                    <div class="row" flex="true" style="display: flex; justify-content: center;">

                        <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>


                <!-- /.social-auth-links -->

            </div>
            <!-- /.register-card-body -->
        </div>
    </main>

@endsection
