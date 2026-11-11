<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Visitantes / Autenticação e Cadastro Público)
|--------------------------------------------------------------------------
*/

// Login (GET /login e POST /login com limite de tentativas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.attempt');

// Cadastro público de usuários (independente)
Route::get('/register', [UserController::class, 'create'])->name('register');
Route::get('/user-create', [UserController::class, 'create'])->name('users.create');
Route::post('/store', [UserController::class, 'store'])->name('users.store');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Qualquer usuário autenticado)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Rotas Administrativas (/admin/*) - Protegidas por AdminMiddleware
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware([AdminMiddleware::class])
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Rotas auxiliares preservadas sob proteção administrativa
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user-update/{id}', [UserController::class, 'edit'])->name('user.update.form');
        Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::put('/user-update/{id}', [UserController::class, 'update']);
        Route::get('/user-delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
        Route::delete('/user-delete/{id}', [UserController::class, 'destroy']);
        Route::get('/user-profile/{id}', [UserController::class, 'show'])->name('user.profile');
        Route::get('/user-with-profile', [UserController::class, 'index'])->name('user.with.profile');
        Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('password.forgot');
        Route::post('/update-password', [UserController::class, 'updatePassword'])->name('password.update');
    });
