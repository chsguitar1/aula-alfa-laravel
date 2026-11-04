<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// Página inicial: exige apenas autenticação (visitante vai para /login).
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Autenticação (públicas). O limite de tentativas é aplicado dentro de AuthController::login.
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.store');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Cadastro público: sempre cria conta comum (nunca admin).
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Todas as rotas administrativas ficam dentro deste grupo protegido por EnsureAdmin.
Route::prefix('admin')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('/users/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
