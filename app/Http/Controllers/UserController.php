<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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

   public function formRecuperarSenha()
   {
       return view('user.forgot-password');
   }

   public function alterarSenha(Request $request)
   {
       $request->validate([
           'email'    => 'required|email|exists:users,email',
           'password' => 'required|min:8|confirmed',
       ], [
           'email.required'     => 'Informe o email.',
           'email.email'        => 'Email inválido.',
           'email.exists'       => 'Nenhum usuário com esse email.',
           'password.required'  => 'Informe a nova senha.',
           'password.min'       => 'A senha precisa ter no mínimo 8 caracteres.',
           'password.confirmed' => 'As senhas não conferem.',
       ]);

       $user = User::where('email', $request->email)->first();
       $user->password = $request->password;
       $user->save();

       return back()->with('success', 'Senha alterada com sucesso!');
   }

}