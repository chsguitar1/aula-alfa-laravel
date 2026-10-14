<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        
    }

    public function show(User $user)
    {
        
    }

    public function register()
    {
        
    }

    public function create(Request $request)
    {
        
    }

    
    public function showForgotPasswordForm()
    {
        return view('user.forgot-password'); 
    }

    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')->with('success', 'Palavra-passe alterada com sucesso!');
    }
}
