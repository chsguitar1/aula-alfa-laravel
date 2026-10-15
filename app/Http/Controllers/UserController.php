<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);

        return view('user.index', [
            'users' => $users,
            'title' => 'Lista de usuario do Sistema',
        ]);
    }

    public function show(User $user)
    {
        return view('user.show', [
            'user' => $user,
        ]);
    }

    public function resetForm(User $user)
    {
        return view('user.resetForm', [
            'user' => $user,
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Solicitação de nova senha enviada para '.$request->email);
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

        $input['password'] = Hash::make($input['password']);

        User::create($input);

        return redirect()->route('admin.users.index');
    }
}
