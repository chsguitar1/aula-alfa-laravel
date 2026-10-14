<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/users',[UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}' ,[UserController::class, 'show']);

Route::get('register', [UserController::class, 'register']);
Route::post('store', [UserController::class, 'create'])->name('users.store');
// Rota para exibir o formulário de recuperação/alteração de senha
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('password.request');

// Rota para submissão do formulário (POST)
Route::post('/forgot-password', [UserController::class, 'updatePassword'])->name('password.update');

// Rota GET para abrir o formulário de recuperação de senha
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('password.request');

// Rota POST para enviar o formulário e alterar a senha
Route::post('/forgot-password', [UserController::class, 'updatePassword'])->name('password.update');