<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;    

class UserController extends Controller
{
    
   public function index(){
    $users = User::paginate(5);

    return view('user.index',[
        'users' => $users,
        'title' => 'Lista de usuario do Sistema'
    ]);
   }

   public function show(int $id)
   {
    $user = new User();
    $user = $user->show_user($id);
  
    return view('user.show',[
        'user' => $user]);

   }

   public function register()
   {
    return view('user.create');
   }

   public function create(Request $request)
   {
    
    $input = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    User::create($input);
    return redirect()->route('admin.users.index');
   }

    // Exibe a página/formulário de recuperação de senha
    public function showLinkRequestForm()
    {
        return view('user.esqueciSenha');
    }

    // Processa o POST do formulário para alterar a senha
    public function alterarSenha(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed', // 'confirmed' procura por um campo password_confirmation
        ]);

        // Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Atualiza a senha criptografada
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Senha alterada com sucesso!');
    }
   

}
