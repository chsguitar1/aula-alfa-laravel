<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;

// Página inicial protegida.
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Login.
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

// Logout.
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Cadastro (Dev 2).
Route::get('/register', [UserController::class, 'register'])
    ->name('register');

Route::post('/store', [UserController::class, 'store'])
    ->name('users.store');

// Área administrativa (Dev 2).
Route::prefix('admin')
    ->middleware(EnsureAdmin::class)
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');
    });
