<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', fn () => view('welcome'))->middleware('auth')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')->name('login.store');
});

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/store', [UserController::class, 'store'])->name('users.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::put('/users/{user}/languages', [UserController::class, 'updateLanguages'])->name('user.updateLanguages');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});
