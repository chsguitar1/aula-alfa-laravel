<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}', [UserController::class, 'show']);

Route::get('register', [UserController::class, 'register']);
Route::post('store', [UserController::class, 'create'])->name('users.store');

Route::get('forgot-password', [UserController::class, 'forgotPassword'])->name('password.request');
Route::post('forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [UserController::class, 'resetPasswordForm'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'updatePassword'])->name('password.update');
