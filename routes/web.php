<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.users.index');
})->name('home');

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}', [UserController::class, 'show']);

Route::get('/register', [UserController::class, 'register'])->name('users.create');
Route::post('/register', [UserController::class, 'create'])->name('users.store');

Route::get('/reset-password', [UserController::class, 'showPasswordChangeForm'])->name('password.edit');
Route::post('/reset-password', [UserController::class, 'updatePassword'])
    ->middleware('throttle:5,1')
    ->name('password.update');
