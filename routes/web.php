<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])
    ->middleware('throttle:login')
    ->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::get('/register', [UserController::class, 'register'])->name('register');

Route::post('/store', [UserController::class, 'store'])->name('users.store');

Route::prefix('admin')->name('admin.')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::put('/users/{user}/languages', [UserController::class, 'updateLanguages'])->name('user.updateLanguages');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});
