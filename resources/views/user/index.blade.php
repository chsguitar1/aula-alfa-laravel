@extends('layouts.default')
@section('classe-body', 'register-page', 'register-page bg-body-secondary')
@section('content')
</main>
    <main>
    @if (session('status'))
    <p>{{ session('status') }}</p>
    @endif
    @foreach ($users as $user )
    <h1> Nome: {{ $user->name }}</h1>
    <p> Enail: {{ $user->email }}</p>

    @if($user->id === 10)
        <p>Usuario com id 10</p>
    @else
        <p>Usuario com id  <> 10</p>
    @endif

    @endforeach

    {{ $users->links() }}
</main>
@endsection
