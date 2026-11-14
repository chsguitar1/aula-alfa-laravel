<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    if (Auth::check() && Auth::user()->profile?->type === 'admin') {
        return redirect()->route('admin.users.index')
            ->with('status', 'Usuário cadastrado com sucesso!');
    }

    return redirect('/login')->with('status', 'Cadastro realizado! Faça login.');
   }

   public function forgotPassword()
   {
    return view('user.forgot-password');
   }

   public function changePassword(Request $request)
   {
    $input = $request->validate([
        'email' => 'required|string|email|exists:users,email',
        'password' => 'required|string|min:8',
    ]);

    $user = User::where('email', $input['email'])->first();
    $user->password = $input['password'];
    $user->save();

    return redirect('/login')->with('status', 'Senha alterada! Faça login.');
   }
   
}
