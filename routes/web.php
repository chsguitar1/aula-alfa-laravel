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
<<<<<<< HEAD
Route::post('store', [UserController::class, 'create'])->name('users.store');

Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('user.forgnot');

Route::post('/forgot-password', [UserController::class, 'updatePassword'])->name('user.password.update');
=======
Route::post('store', [UserController::class, 'create'])->name('users.store');
>>>>>>> 809676c2b0296ab7cb5f8b0d616893aea033e9df
