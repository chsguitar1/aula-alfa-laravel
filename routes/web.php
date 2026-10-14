use App\Http\Controllers\UserController;

Route::get('/esqueci-senha', [UserController::class, 'esqueciSenha'])
    ->name('user.esqueci-senha');

Route::post('/esqueci-senha', [UserController::class, 'alterarSenha'])
    ->name('user.alterar-senha');