<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('register', [UserController::class, 'register'])->name('register');

Route::post('store', [UserController::class, 'store'])->name('users.store');

// Todas as rotas administrativas exigem login e perfil admin.
Route::prefix('admin')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('/users/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
