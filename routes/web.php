
<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/users', [UserController::class, 'index'])
    ->name('admin.users.index');

Route::get('admin/user/{user}', [UserController::class, 'show']);

Route::get('register', [UserController::class, 'register']);

Route::post('store', [UserController::class, 'create'])
    ->name('users.store');

// Página de recuperação de senha
Route::get('/recuperar-senha', [UserController::class, 'recovery'])
    ->name('password.recovery');

// Processar alteração de senha
Route::post('/alterar-senha', [UserController::class, 'changePassword'])
    ->name('password.change');
