<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\AreaCientifica;

class ProfileProfessorController extends Controller
{
    /**
     * Exibe o perfil do professor com os seus dados institucionais e áreas científicas.
     */
    public function edit()
    {
        try{
        $user = Auth::user();
        
        // Eager Loading das relações do professor e contagens estatísticas das orientações
        $professor = $user->professor()
            ->with(['departamentos', 'areas'])
            ->withCount(['estudantes', 'coorientados'])
            ->first();

        if (!$professor) {
            return redirect()->back()->with('error', 'Perfil de professor não localizado.');
        }

        // Busca todas as áreas científicas disponíveis no sistema para a multi-seleção
        $todasAreas = AreaCientifica::orderBy('nome', 'asc')->get();

        return view('app.private.dashboard.othes.professores.perfil.perfil', compact('user', 'professor', 'todasAreas'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    /**
     * Atualiza os dados pessoais, limite de orientações e as áreas científicas do professor.
     */
    public function update(Request $request)
    {
        try{
        $user = Auth::user();
        $professor = $user->professor;

        $request->validate([
               'name' => [
    'required',
    'string',
    'min:3',
    'max:100',
    'regex:/^[\p{L}\s]+$/u'
],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'max_orientacoes' => ['required', 'integer', 'min:1', 'max:50'], // Validação do novo campo institucional
            'areas' => ['nullable', 'array'],
            'telefone' => ['required'],
            'areas.*' => ['exists:areas_cientificas,id'], 
        ]);
 
            DB::transaction(function () use ($request, $user, $professor) {
                // 1. Atualiza dados de utilizador base
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                                        'telefone' => $request->telefone,
                ]);

                // 2. Atualiza os dados específicos do professor (incluindo o máximo de orientações)
                $professor->update([
                    'max_orientacoes' => $request->max_orientacoes
                ]);

                // 3. Sincroniza as áreas científicas na tabela pivô
                $professor->areas()->sync($request->input('areas', []));
            });

            return redirect()->route('profile.professor.edit')->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar as alterações do perfil.');
        }
    }

    /**
     * Altera a senha do professor.
     */
    public function updatePassword(Request $request)
    {
        try{
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        
            Auth::user()->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('profile.professor.edit')->with('success', 'Senha alterada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar a senha.');
        }
    }
}