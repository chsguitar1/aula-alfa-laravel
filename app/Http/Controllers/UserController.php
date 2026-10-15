<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

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

    public function show(int $id)
    {
        $user = new User;
        $user = $user->show_user($id);

        return view('user.show', [
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

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::defaults()],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'E-mail ou senha atual incorretos.'])
                ->withInput($request->only('email'));
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        return back()->with('status', 'Senha alterada com sucesso.');
    }

    public function showPasswordChangeForm(): View
    {
        return view('user.reset-password');
    }
}
