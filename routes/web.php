<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::get('register', [UserController::class, 'register'])->name('register');

Route::post('store', [UserController::class, 'store'])->name('users.store');

// Protege todas as rotas administrativas com a verificação de perfil admin.
Route::prefix('admin')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
   
});
