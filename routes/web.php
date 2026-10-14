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

//Paginae função para alterar senha
Route::get('resetPassword', [UserController::class, 'pageResetPassword'])->name('user.reset.index');
Route::post('resetPassword', [UserController::class, 'resetPassword'])->name('user.reset.store');
