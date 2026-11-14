<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas (visitantes)
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// 'throttle:login' usa o limitador "login" definido em AppServiceProvider
// (5 tentativas por minuto para cada combinação email + IP).
Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.store');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

/*
|--------------------------------------------------------------------------
| Rotas para usuários autenticados
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Logout apenas via POST (com CSRF): um link GET poderia ser disparado por terceiros.
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Área administrativa
|--------------------------------------------------------------------------
| O middleware "admin" (App\Http\Middleware\EnsureAdmin) é aplicado ao grupo
| inteiro: qualquer rota nova declarada aqui já nasce protegida.
*/
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('users/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('users/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
