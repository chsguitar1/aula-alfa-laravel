<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/recuperar-senha', function () {
    return view('recuperar-senha');
})->name('senha.formulario');

Route::post('/recuperar-senha', [UserController::class, 'alterarSenha'])
    ->name('senha.alterar');
