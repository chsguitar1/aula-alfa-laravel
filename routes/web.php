<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.store');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Protege todas as rotas administrativas com a verificação de perfil admin.
Route::prefix('admin')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
});