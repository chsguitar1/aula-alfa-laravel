<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autenticação (somente visitantes)
|--------------------------------------------------------------------------
| Usuários já autenticados que abrirem /login são redirecionados
| (ver redirectUsersTo em bootstrap/app.php).
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Área autenticada
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Cadastro
|--------------------------------------------------------------------------
| Público: visitantes criam uma conta comum. O mesmo formulário é usado
| pelo administrador (link "Novo Usuario" do painel), por isso não usa "guest".
*/
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('store', [UserController::class, 'store'])->name('users.store');

/*
|--------------------------------------------------------------------------
| Área administrativa
|--------------------------------------------------------------------------
| Todas as rotas /admin/* ficam dentro deste grupo protegido pelo
| middleware "admin" (App\Http\Middleware\EnsureAdmin):
| visitante -> /login, usuário comum -> 403, perfil admin -> segue.
*/
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('user/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('user/{user}/profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::put('user/{user}/languages', [UserController::class, 'updateLanguages'])->name('admin.user.updateLanguages');
    Route::delete('user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
});
