<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Lista os usuários no painel administrativo (GET /admin/users).
     */
    public function index(): View
    {
        $users = User::with('profile')->get();

        return view('user.index', compact('users'));
    }

    /**
     * Exibe o formulário independente de cadastro de usuário (GET /register).
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * Valida e cria uma conta de usuário comum (POST /store).
     * Nunca cria administradores por padrão no cadastro público.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Sempre cria perfil comum ('user'), nunca 'admin'
        Profile::create([
            'user_id' => $user->id,
            'type' => 'user',
            'description' => 'Usuário comum',
        ]);

        // Se um administrador autenticado cadastrou pelo painel, retorna à lista administrativa
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()
                ->route('users.index')
                ->with('success', 'Usuário cadastrado com sucesso!');
        }

        // Após cadastro público, encaminha o visitante para o login com confirmação
        return redirect()
            ->route('login')
            ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    /**
     * Exibe detalhes de um usuário específico (GET /admin/users/{id}).
     */
    public function show(string $id): View
    {
        $user = User::with('profile')->findOrFail($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Exibe o formulário de edição de usuário (GET /admin/users/{id}/edit).
     */
    public function edit(string $id): View
    {
        $user = User::with('profile')->findOrFail($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Atualiza os dados de um usuário (PUT /admin/users/{id}).
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (array_key_exists('address', $validated)) {
            $user->address = $validated['address'];
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove um usuário (DELETE /admin/users/{id}).
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->profile) {
            $user->profile->delete();
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Exibe a tela de recuperação de senha.
     */
    public function forgotPassword(): View
    {
        return view('user.forgot-password');
    }

    /**
     * Atualiza a senha pelo fluxo administrativo.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::where('email', $request->input('email'))->firstOrFail();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Senha atualizada com sucesso!');
    }
}
