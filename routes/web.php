<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/users',[UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}' ,[UserController::class, 'show']);

Route::get('register', [UserController::class, 'register']);
Route::post('store', [UserController::class, 'create'])->name('users.store');

Route::get('forgot-password', [UserController::class, 'forgotPassword'])->name('users.forgot-password');
Route::post('forgot-password', [UserController::class, 'sendPasswordResetLink'])->name('users.password-email');
Route::get('reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'resetPassword'])->name('password.update');