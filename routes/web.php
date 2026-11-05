<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// Página inicial: exige usuário autenticado (visitantes vão para /login).
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Login: formulário público e envio com limite de tentativas (limiter "login").
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.store');

// Logout: somente via POST (com CSRF) e somente para quem está autenticado.
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Cadastro público (cria sempre uma conta comum).
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Todas as rotas administrativas passam por EnsureAdmin:
// visitante -> /login, autenticado sem perfil admin -> 403.
Route::prefix('admin')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('users/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('users/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
