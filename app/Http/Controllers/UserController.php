<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->paginate(5);

        return view('user.index', [
            'users' => $users,
            'title' => 'Lista de usuários do Sistema',
        ]);
    }

    public function show(User $user)
    {
        return view('user.show', [
            'user' => $user,
        ]);
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withInput(['email' => $request->email])
                ->withErrors(['email' => 'Credenciais inválidas.']);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user?->profile?->type === 'admin') {
            return redirect()->intended(route('admin.users.index'));
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function register()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        if ($request->user() && $request->user()->profile?->type !== 'admin') {
            abort(403, 'Apenas administradores podem cadastrar usuários autenticados.');
        }

        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create($input);

        if ($request->user()?->profile?->type === 'admin') {
            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
        }

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso. Faça login para continuar.');
    }

    public function edit(User $user)
    {
        $user->load('profile', 'languages');

        return view('user.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($input);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function updateProfile(User $user, Request $request)
    {
        $input = $request->validate([
            'type' => ['required', 'string', 'in:PJ,PF,admin'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $input['description'] = $user->profile?->description ?? '';

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $input,
        );

        return redirect()->route('admin.users.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updateLanguages(User $user, Request $request)
    {
        $input = $request->validate([
            'languages' => ['nullable', 'array'],
            'languages.*' => ['string', 'distinct', 'in:PHP,JavaScript,Python,Java,C#'],
        ]);

        DB::transaction(function () use ($user, $input) {
            $user->languages()->delete();
            $user->languages()->createMany(array_map(
                fn (string $language) => ['language' => $language],
                $input['languages'] ?? [],
            ));
        });

        return redirect()->route('admin.users.index')->with('success', 'Linguagens atualizadas com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
