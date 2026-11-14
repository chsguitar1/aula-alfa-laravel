<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', [
            'user' => $user]);

    }

    public function register()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create($input);

        if ($request->user()?->profile?->type === 'admin') {
            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
        }

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso. Faça login para continuar.');

    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user->load('profile', 'languages');

        return view('user.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->validate([
            'name' => 'required|string|max:255',
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
            'type' => 'required|string|in:PJ,PF',
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $input
        );

        return redirect()->route('admin.users.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updateLanguages(User $user, Request $request)
    {
        $input = $request->validate([
            'languages' => 'nullable|array',
            'languages.*' => 'distinct|string|in:PHP,JavaScript,Python,Java,C#',
        ]);
        DB::transaction(function () use ($user, $input) {
            $user->languages()->delete();
            $user->languages()->createMany(array_map(fn ($language) => ['language' => $language], $input['languages'] ?? []));
        });

        return redirect()->route('admin.users.index')->with('success', 'Linguagens atualizadas com sucesso!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
