<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Página inicial: exige usuário autenticado (visitante vai para /login).
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Autenticação. O login tem limite de tentativas (limiter "login" em AppServiceProvider).
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:login')->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Cadastro público: sempre cria usuário comum.
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Toda rota administrativa fica dentro deste grupo, protegido pelo middleware "admin" (EnsureAdmin).
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->whereNumber('id')->name('admin.user.show');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->whereNumber('id')->name('admin.user.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->whereNumber('id')->name('admin.user.update');
    Route::put('users/{user}/profile', [UserController::class, 'updateProfile'])->whereNumber('user')->name('admin.user.updateProfile');
    Route::put('users/{user}/languages', [UserController::class, 'updateLanguages'])->whereNumber('user')->name('admin.user.updateLanguages');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->whereNumber('id')->name('admin.user.destroy');
});
