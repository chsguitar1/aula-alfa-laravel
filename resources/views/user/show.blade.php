@extends('layouts.default')

@section('title','Ver Usuário')

@section('content')
<div>
   
    <h1>Email: {{ $user->email }}</h1>
    <h2>Nome: {{ $user->name }}</h2>


</div>
@endsection