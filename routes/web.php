<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
// "throttle:login" -> limitador definido em AppServiceProvider (5 tentativas/min por email+IP).
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

/*
|--------------------------------------------------------------------------
| Rotas para qualquer usuário autenticado
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Área administrativa: TODAS as rotas /admin/* passam pelo middleware "admin"
| (visitante -> /login, autenticado sem perfil admin -> 403).
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('user/{user}', [UserController::class, 'show']);
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('user/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('user/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
