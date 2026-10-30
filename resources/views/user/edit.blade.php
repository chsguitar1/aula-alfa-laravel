@extends('layouts.default')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Editar usuário</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->has('profile'))
                    <div class="alert alert-danger">
                        {{ $errors->first('profile') }}
                    </div>
                @endif

                <form action="{{ route('admin.user.update', $user->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Dados do usuário</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Salvar dados
                    </button>
                </form>

                <hr class="my-4">

                <h5 class="mb-3">Perfil</h5>

                @if($user->profile?->type === 'admin')
                    <div class="alert alert-info">
                        Este usuário possui perfil administrativo.
                        O tipo não pode ser alterado neste formulário.
                    </div>
                @else
                    <form action="{{ route('admin.user.profile.update', $user->id) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="type" class="form-label">
                                Tipo de pessoa
                            </label>
                            <select name="type"
                                    id="type"
                                    class="form-select @error('type') is-invalid @enderror"
                                    required>
                                @foreach(['PF', 'PJ'] as $type)
                                    <option value="{{ $type }}"
                                        @selected(old('type', $user->profile?->type ?? 'PF') === $type)>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>

                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Descrição
                            </label>
                            <input type="text"
                                   id="description"
                                   name="description"
                                   value="{{ old('description', $user->profile?->description) }}"
                                   class="form-control @error('description') is-invalid @enderror"
                                   maxlength="255">

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Salvar perfil
                        </button>
                    </form>
                @endif

            </div>

            <div class="card-footer">
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-secondary">
                    Voltar para usuários
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
