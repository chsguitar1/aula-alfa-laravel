<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.attempt');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'create'])->name('users.store');

Route::get('/recuperar-senha', [UserController::class, 'forgotPassword'])->name('user.password.form');
Route::post('/recuperar-senha', [UserController::class, 'updatePassword'])->name('user.password.update');

// Autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Administrativas: o middleware cobre todo o conjunto
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/user/{user}', [UserController::class, 'show']);
});