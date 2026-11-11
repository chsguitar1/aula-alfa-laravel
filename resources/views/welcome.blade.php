@extends('layouts.default')

@section('title', 'Página Inicial | Rede Patas')

@section('content')
<div class="card bg-dark border-secondary mt-2">
    <div class="card-body">
        <h4 class="card-title mb-3">Bem-vindo(a), {{ Auth::user()->name }}!</h4>
        <p class="card-text text-secondary mb-3">
            Você está autenticado na página inicial (<code>GET /</code>) com o perfil:
            <span class="badge {{ Auth::user()->isAdmin() ? 'text-bg-primary' : 'text-bg-secondary' }}">
                {{ Auth::user()->profile?->type ?? 'user' }}
            </span>
        </p>
        <div class="d-flex gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                Acessar Painel Administrativo (/admin/users)
            </a>
        </div>
    </div>
</div>
@endsection
