<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [UserController::class, 'register'])->name('admin.users.create');
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'create'])->name('users.store');

Route::get('/admin/user/{user}', [UserController::class, 'show'])->name('admin.users.show');

Route::get('/admin/user/{user}/resetForm', [UserController::class, 'resetForm'])->name('admin.users.resetForm');

Route::post('/admin/user/{user}/resetForm', [UserController::class, 'resetPassword'])->name('admin.users.reset');
