@extends('layouts.default')

@section('title', 'Ver Usuário')
@section('classe-body', 'bg-body-secondary')

@section('content')
    <main class="container py-5" style="max-width: 600px">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h1 class="card-title h5 mb-0">Detalhes do usuário</h1>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9">{{ $user->id }}</dd>

                    <dt class="col-sm-3">Nome</dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9 mb-0">{{ $user->email }}</dd>
                </dl>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </main>
@endsection
