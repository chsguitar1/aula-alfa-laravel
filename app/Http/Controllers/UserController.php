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

    public function alterarSenha(Request $request)
{
    $input = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::where('email', $input['email'])->first();

    if (!$user) {
        return back()
            ->withErrors(['email' => 'E-mail não encontrado.'])
            ->withInput($request->only('email'));
    }

    $user->password = Hash::make($input['password']);
    $user->save();

    return redirect()
        ->route('senha.formulario')
        ->with('success', 'Senha alterada com sucesso!');
}

}
