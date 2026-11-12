<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'loginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [UserController::class, 'register'])->name('register');
});

Route::post('/store', [UserController::class, 'store'])->name('users.store');

Route::middleware(['auth', EnsureAdmin::class])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'register'])->name('admin.users.create');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('/users/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
