@extends('layouts.default')

@section('title', 'Atualizar Senha | Rede Patas')

@section('content')
<div class="card bg-dark border-secondary mt-2" style="max-width: 500px;">
    <div class="card-header border-secondary">
        <h5 class="card-title mb-0">Redefinir Senha de Usuário</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('password.update') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nova Senha</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required />
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Senha</button>
        </form>
    </div>
</div>
@endsection
