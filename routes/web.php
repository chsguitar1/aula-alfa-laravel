<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// Página inicial: apenas para quem está autenticado (visitante vai para /login).
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Autenticação (rotas públicas).
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.store');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Cadastro (público). O POST sempre cria um usuário comum.
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Todas as rotas administrativas passam pelo middleware EnsureAdmin:
// visitante -> /login, autenticado sem perfil admin -> 403.
Route::prefix('admin')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('user/{user}', [UserController::class, 'show']);
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('user/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('user/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
