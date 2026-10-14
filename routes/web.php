<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    return back()->with('status', 'Link de recuperação enviado para o e-mail informado.');
})->name('password.email');

Route::get('/admin/users',[UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/user/{user}' ,[UserController::class, 'show']);

Route::get('register', [UserController::class, 'register']);
Route::post('store', [UserController::class, 'create'])->name('users.store');