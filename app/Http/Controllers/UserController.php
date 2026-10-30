<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    // Lista os usuários cadastrados.
    public function index()
    {
        $users = User::paginate(5);

        return view('user.index', [
            'users' => $users,
            'title' => 'Lista de usuários do Sistema',
        ]);
    }

    // Exibe os dados de um usuário.
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', [
            'user' => $user,
        ]);
    }

    // Exibe o formulário de cadastro.
    public function register()
    {
        return view('user.create');
    }

    // Cadastra um usuário com perfil comum.
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ]);

        // Verifica se o cadastro foi feito por um administrador.
        $isAdmin = $request->user()?->profile?->type === 'admin';

        // Cria usuário e perfil na mesma transação.
        DB::transaction(function () use ($input) {
            $user = User::create($input);

            // Todo cadastro recebe perfil comum.
            // O cadastro público nunca cria administradores.
            $user->profile()->create([
                'type' => 'PF',
                'description' => 'Usuário comum',
            ]);
        });

        if ($isAdmin) {
            return redirect()
                ->route('admin.users.index')
                ->with(
                    'success',
                    'Usuário criado com sucesso!'
                );
        }

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Cadastro realizado com sucesso. Faça login para continuar.'
            );
    }

    // Exibe o formulário de edição.
    public function edit($id)
    {
        // Carrega somente o perfil.
        // O projeto inicial não possui a tabela de linguagens.
        $user = User::with('profile')->findOrFail($id);

        return view('user.edit', [
            'user' => $user,
        ]);
    }

    // Atualiza nome e email.
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $input = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],
        ]);

        $user->update($input);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Usuário atualizado com sucesso!'
            );
    }

    // Atualiza o perfil do usuário.
    public function updateProfile(User $user, Request $request)
    {
        // Impede alterações em perfis administrativos
        // através do formulário comum de edição.
        if ($user->profile?->type === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->withErrors([
                    'profile' =>
                        'O perfil administrativo não pode ser alterado por este formulário.',
                ]);
        }

        $input = $request->validate([
            'type' => [
                'required',
                'string',
                'in:PJ,PF',
            ],
            'description' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        // Atualiza ou cria o perfil.
        $user->profile()->updateOrCreate(
            [],
            [
                'type' => $input['type'],
                'description' => $input['description'] ?? '',
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Perfil atualizado com sucesso!'
            );
    }

    // Exclui um usuário.
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Usuário excluído com sucesso!'
            );
    }
}
