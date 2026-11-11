@extends('layouts.default')

@section('title', 'Início')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Olá, {{ auth()->user()->name }}!</h5>

            <p class="mb-3">Você está autenticado no sistema.</p>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Listar Usuário</a>
            @else
                <p class="text-secondary mb-0">Sua conta é comum e não tem acesso à área administrativa.</p>
            @endif
        </div>
    </div>
@endsection
