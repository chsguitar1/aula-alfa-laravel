<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);

        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Lista de usuario do Sistema'
        ]);
    }

    public function show(int $id)
    {
        $user = new User();
        $user = $user->show_user($id);
    
        return view('user.show',[
            'user' => $user
        ]);
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

        // Criptografa a senha antes de salvar no banco
        $input['password'] = Hash::make($input['password']);

        User::create($input);

        // Se quem cadastrou foi um admin logado pelo painel, volta para a lista. Senão, vai para o login[cite: 7]
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
        }

        return redirect()->route('login')->with('success', 'Conta criada com sucesso! Faça o login.');
    }

    public function forgotPassword()
    {
        return view('user.forgot-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Senha alterada com sucesso!');
    }
}