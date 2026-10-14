<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

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

    //Função para ir na pagina de recuperar senha
    public function pageResetPassword(): View
    {
        return view('user.resetPassword');
    }

    //Função de validação de recuperação de senha
    public function resetPassword(Request $request)
    {
        $input = $request->validate(['email' => 'required|email']);

        if(!$input) return view('user.resetPassword');

        // Volta para o formulário e envia a mensagem 'Aprovado!' (chave 'success') para a view mostrar
        return back()->with('success', 'Aprovado!');

    }

}
