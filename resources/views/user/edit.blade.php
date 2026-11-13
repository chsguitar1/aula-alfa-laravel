@extends('layouts.default')
@section('classe-body', 'register-page', 'register-page bg-body-secondary')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <main class="register-box w-100 mx-auto" style="max-width: 900px;">
                <h1 class="register-logo text-center">
                    <a href="../index2.html"><b>Bem vindo</b></a>
                </h1>
                <div class="card">
                    <div class="card-body register-card-body">
                        <p class="register-box-msg">Preencha os campos para se registrar</p>

                        <form action="{{ route('admin.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        Dados do usuário
                    </div>
                    <div class="card-body">
                        <label class="visually-hidden" for="name">Nome</label>
                        <div class="input-group mb-3">
                            <input id="name" name="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Nome"
                                value="{{ $user->name }}" />
                            <div class="input-group-text">
                                <span class="bi bi-person"></span>
                            </div>
                        </div>
                        <label class="visually-hidden" for="email">Email</label>
                        <div class="input-group mb-3">
                            <input id="email" name="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                value="{{ $user->email }}" />
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                            <div class="invalid-feedback">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>



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
                <br>
                <form action="{{ route('admin.user.updateProfile', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        Perfil
                    </div>
                    <div class="card-body">
                        <label for="type">Tipo de pessoa</label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                            @foreach (['PJ', 'PF'] as $type)
                                <option value="{{ $type }}" @selected((old('type') ?? $user->profile?->type) == $type)>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('type')
                                {{ $message }}
                            @enderror
                        </div>
                        <label class="visually-hidden" for="address">Endereço</label>
                        <div class="input-group mb-3">
                            <input id="address" name="address" type="text"
                                class="form-control @error('address') is-invalid @enderror" placeholder="Endereço"
                                value="{{ old('address') ?? $user->profile?->address }}" />
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                            <div class="invalid-feedback">
                                @error('address')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>



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

                <br>

                <form action="{{ route('admin.user.updateLanguages', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        Linguagens de programação
                    </div>

                    <div class="card-body">
                        @foreach (['PHP', 'JavaScript', 'Python', 'Java', 'C#'] as $language)
                            <div class="form-check mb-3">
                                {{-- <input class="form-check-input  @error('languages') is-invalid @enderror" type="checkbox"
                                    id="check-{{ $language }}" name="languages[][language]"
                                    value="{{ $language }}" @if ($user->languages->contains('language', $language)) checked @endif> --}}

                                    <input class="form-check-input  @error('languages') is-invalid @enderror" type="checkbox"
                                    id="check-{{ $language }}" name="languages[][language]"
                                    value="{{ $language }}" @checked(in_array($language, $user->languages->pluck('language')->toArray()))>
                                <label class="form-check-label"
                                    for="check-{{ $language }}">{{ $language }}</label>
                            </div>
                            @if ($loop->last)
                                <div class="invalid-feedback">
                                    @error('languages')
                                        {{ $message }}
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                    </div>
                    <div class="card-footer"></div>



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
        </div>
    </div>
@endsection
