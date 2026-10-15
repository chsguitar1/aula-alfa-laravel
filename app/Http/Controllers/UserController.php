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

   public function forgotPassword()
   {
    return view('user.forgot-password');
   }

   public function updatePassword(Request $request)
   {
    $input = $request->validate([
        'email' => 'required|string|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'email.exists' => 'Nenhum usuário encontrado com este e-mail.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        'password.confirmed' => 'A confirmação da senha não confere.',
    ]);

    $user = User::where('email', $input['email'])->firstOrFail();
    // o cast "hashed" do model User já faz o hash da senha
    $user->update(['password' => $input['password']]);

    return redirect()
        ->route('users.password.forgot')
        ->with('success', 'Senha alterada com sucesso!');
   }
   

}
