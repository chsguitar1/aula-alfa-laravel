<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas (qualquer visitante)
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// "throttle:login" = limite de tentativas (configurado em AppServiceProvider).
Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.store');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

/*
|--------------------------------------------------------------------------
| Rotas para quem está autenticado (logado)
|-------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Rotas administrativas (/admin/*)
|--------------------------------------------------------------------------
| TODAS ficam dentro deste grupo, protegido pelo middleware "admin"
| (App\Http\Middleware\EnsureAdmin):
|   - visitante             -> redireciona para /login
|   - logado, mas não admin -> 403
|   - perfil type = admin   -> passa
*/
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    // "user/create" precisa vir ANTES de "user/{user}", senão "create" seria lido como um id.
    Route::get('user/create', [UserController::class, 'adminCreate'])->name('admin.user.create');
    Route::get('user/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('user/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::delete('user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});