<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            // Nunca cria administrador pelo formulário público.
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
        // Impede alterar o perfil administrativo
        // pelo formulário comum de edição.
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

        // Cria ou atualiza o perfil.
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

    // Exibe a página de recuperação de senha.
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Solicita o link de recuperação por email.
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Impede solicitações repetidas rapidamente.
        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors([
                'email' => 'Aguarde antes de solicitar outro link.',
            ]);
        }

        // Não informa se o email existe no banco.
        return back()->with(
            'status',
            'Se o email estiver cadastrado, você receberá um link para recuperar sua senha.'
        );
    }

    // Exibe o formulário para definir uma nova senha.
    public function resetPasswordForm(
        Request $request,
        string $token
    ) {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    // Valida o token e altera a senha.
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {
                // Salva a senha criptografada.
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                // Renova o token de lembrar login.
                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Token inválido, expirado ou email incorreto.
        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' =>
                        'Não foi possível alterar a senha. Solicite um novo link de recuperação.',
                ]);
        }

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Senha alterada com sucesso! Faça login com a nova senha.'
            );
    }
}
