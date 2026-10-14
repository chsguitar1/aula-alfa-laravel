use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

public function esqueciSenha()
{
    return view('user.esqueci-senha');
}

public function alterarSenha(Request $request)
{
    $dados = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    // Aqui entraria a gravação no banco:
    // $user = User::where('email', $dados['email'])->firstOrFail();
    // $user->password = Hash::make($dados['password']);
    // $user->save();

    $senhaCriptografada = Hash::make($dados['password']);

    return redirect()
        ->route('user.esqueci-senha')
        ->with('success', 'Senha alterada com sucesso!');
}