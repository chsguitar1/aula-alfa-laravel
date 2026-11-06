<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->middleware('autenyticado');


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function(){
    Route::get('/users', [UserController::class, 'index'])
    ->name('admin.users.index');
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::get('/register',[UserController::class, 'register'])
    ->name('admin.users.create');
    Route::post('/users', [UserController::class,'create'])
    ->name('admin.users.store');

});

Route::get('/login', function(){
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])
->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])
->middleware('auth')
->name('logout');

Route::get('register', [UserController::class, 'register']);
Route::post('store', [UserController::class, 'create'])->name('users.store');

Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('users.password.form');
Route::post('/forgot-password', [UserController::class, 'sendPasswordResetLink'])->name('users.password.email');
Route::get('/reset-password/{token}', [UserController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [UserController::class, 'updatePassword'])->name('users.password.update');
