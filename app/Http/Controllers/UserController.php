<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Exibe a tela de recuperação
   public function forgotPassword()
    {
        // O "user." avisa o Laravel para procurar dentro da pasta resources/views/user/
        return view('user.forgot-password');
    }

    // Processa a alteração da senha
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.exists' => 'Não foi encontrado nenhum utilizador com este e-mail.',
            'password.required' => 'A nova senha é obrigatória.',
            'password.confirmed' => 'A confirmação de senha não coincide.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('status', 'Senha alterada com sucesso!');
    }
}