<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// Home: somente autenticados.
Route::get('/', fn () => view('welcome'))->middleware('auth')->name('home');

// Visitantes: login e cadastro.
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');

    Route::get('register', [UserController::class, 'register'])->name('register');
    Route::post('store', [UserController::class, 'store'])->name('users.store');
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Todas as rotas administrativas passam pelo middleware.
Route::prefix('admin')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('users/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('users/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
