<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SecretariaController extends Controller
{
    public function index()
    {
        // Lista apenas usuários que possuem a role secretaria
        $secretarias = User::role('secretaria')->latest()->paginate(10);
        return view('app.private.dashboard.othes.secretarias.index', compact('secretarias'));
    }

    public function create()
    {
        return view('app.private.dashboard.othes.secretarias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Criar usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // 2. Atribuir role
            $user->assignRole('secretaria');

            DB::commit();

            return redirect()->route('secretarias.index')
                ->with('success', 'Secretaria cadastrada com sucesso!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar secretaria: ' . $e->getMessage());
            return back()->with('error', 'Erro ao registrar secretaria.')->withInput();
        }
    }
    public function destroy($id)
{
    try {
        $user = User::findOrFail($id);

        // Opcional: Impedir que um usuário exclua a si mesmo
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Você não pode eliminar a sua própria conta.');
        }

        DB::beginTransaction();

        // Remove todas as roles vinculadas antes de deletar (Boa prática do Spatie)
        $user->syncRoles([]);
        
        // Deleta o usuário
        $user->delete();

        DB::commit();

        return redirect()->route('secretarias.index')
            ->with('success', 'Secretaria eliminada com sucesso!');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Erro ao eliminar secretaria: ' . $e->getMessage());
        return back()->with('error', 'Erro ao tentar eliminar o registro.');
    }
}
}