
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;


Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

// Cadastro público
Route::get('/register', [UserController::class, 'register'])
    ->name('register');

Route::post('/store', [UserController::class, 'store'])
    ->name('users.store');

// Página inicial: somente usuários autenticados.
Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Logout: somente usuários autenticados.
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rotas administrativas: somente administradores.
Route::prefix('admin')
    ->middleware(EnsureAdmin::class)
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');
    });
