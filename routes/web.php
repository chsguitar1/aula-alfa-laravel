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

// Rota GET para exibir a página do formulário de recuperação
fn() => null;
Route::get('/esqueci-senha', [UserController::class, 'showLinkRequestForm'])->name('password.request');

// Rota POST para enviar os dados do formulário
Route::post('/esqueci-senha', [UserController::class, 'alterarSenha'])->name('password.update');