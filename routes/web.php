<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->middleware('autenyticado');


Route::get('register', [UserController::class, 'register'])->name('register');

Route::post('store', [UserController::class, 'store'])->name('users.store');

// Protege todas as rotas administrativas com a verificação de perfil admin.
Route::prefix('admin')->middleware(EnsureAdmin::class)->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
   
});

Route::get('forgot-password', [UserController::class, 'forgotPassword'])->name('password.request');
Route::post('forgot-password', [UserController::class, 'updatePassword'])->name('password.update');