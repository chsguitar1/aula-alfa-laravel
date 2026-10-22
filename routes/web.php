<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('register', [UserController::class, 'register'])->name('register');

Route::get('/admin/users',[UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}' ,[UserController::class, 'show']);
Route::post('store', [UserController::class, 'store'])->name('users.store');
Route::get('admin/user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
Route::put('admin/user/{user}', [UserController::class, 'update'])->name('admin.user.update');
Route::put('admin/user/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
Route::delete('admin/user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
Route::put('admin/user/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
