<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}', [UserController::class, 'show']);

Route::get('register', [UserController::class, 'register']);
Route::post('store', [UserController::class, 'create'])->name('users.store');

// Rotas de trocar de senha.
Route::get('users/forgot-password', [UserController::class, 'forgotPassword'])->name('password.forgot');
Route::post('users/forgot-password', [UserController::class, 'updatePassword'])->name('password.update');
