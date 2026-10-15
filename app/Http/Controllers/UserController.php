<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; //ferramenta do laravel q permite criptografar a senha antes de salvá-la no banco de dados

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
   
   public function recuperarSenha()
{
    return view('recuperar-senha');
}

public function alterarSenha(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $usuario = User::where('email', $request->email)->first();

    if (!$usuario) {
        return back()
            ->withErrors(['email' => 'E-mail não encontrado.'])
            ->withInput();
    }

    $usuario->password = Hash::make($request->password);
    $usuario->save();

    return redirect()
        ->route('senha.formulario')
        ->with('sucesso', 'Senha alterada com sucesso!');
}

}
