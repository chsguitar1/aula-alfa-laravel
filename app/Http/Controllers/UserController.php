<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller {
    
    public function showForgotPasswordForm()
    {
        return view('user.forgot-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ]);


        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('user.forgot')->with('success', 'Senha alterada com sucesso!');
        }

        return back()->withErrors(['email' => 'Usuário não encontrado.']);
    }
    }
=======
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
   

}
>>>>>>> 809676c2b0296ab7cb5f8b0d616893aea033e9df
