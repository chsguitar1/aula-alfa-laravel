<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Profile;

class UserController extends Controller
{
    
   public function index(){
    $users = User::paginate(5);

    return view('user.index',[
        'users' => $users,
        'title' => 'Lista de usuario do Sistema'
    ]);
   }

   public function show($id)
   {
    $user = User::find($id);
  
    return view('user.show',[
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
        $user->load('profile');

        return view('user.edit', [
            'user' => $user
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

   public function updateProfile(User $user ,Request $request)
   {
     // Impede alterações no perfil de uma conta administrativa.
    if ($user->profile?->type === 'admin') {
        abort(403, 'O perfil do administrador não pode ser alterado por esta tela.');
    }
       
       $input = $request->validate([
    'type' => 'required|string|in:PJ,PF',
    'address' => 'required|string|max:255',
       ]);
       
       Profile::updateOrCreate(
           ['user_id' => $user->id],
           $input
       );

       return redirect()->route('admin.users.index')->with('success', 'Perfil atualizado com sucesso!');
   }

   public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Impede que o administrador exclua a própria conta.
        if ($request->user()->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode excluir sua própria conta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }


}
