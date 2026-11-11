<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rota Inicial (Exige que o usuário esteja autenticado)
Route::get('/', function () {
    return view('welcome');
})->middleware('auth'); // Corrigido o typo de 'autenyticado' para 'auth'

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rotas de Cadastro Público
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Rotas Administrativas (Protegidas por Autenticação + Perfil Admin)
// Você pode usar o alias 'admin' (se registrou no bootstrap/app.php) ou a classe direta
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index'); 
    // Insira aqui as outras rotas do painel se houverem (edit, update, destroy, etc.)
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
// Rota Inicial (Protegida por autenticação)
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

// Rotas de Autenticação (Login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rotas de Cadastro Público
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

// Rotas Administrativas (Protegidas por Autenticação + Middleware Admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
});