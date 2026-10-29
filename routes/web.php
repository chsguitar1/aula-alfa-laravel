<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureAdmin;

// Página inicial protegida
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Cadastro público
Route::get('/register', [UserController::class, 'register'])
    ->name('register');

Route::post('/store', [UserController::class, 'store'])
    ->name('users.store');

// Área administrativa
Route::prefix('admin')
    ->middleware(['auth', EnsureAdmin::class])
    ->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/{id}', [UserController::class, 'show'])
            ->name('admin.user.show');

        Route::get('/users/{id}/edit', [UserController::class, 'edit'])
            ->name('admin.user.edit');

        Route::put('/users/{id}', [UserController::class, 'update'])
            ->name('admin.user.update');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])
            ->name('admin.user.destroy');

        Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])
            ->name('admin.user.profile.update');
    });
