<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileEstudanteController extends Controller
{
    /**
     * Exibe a view de perfil com os dados do estudante.
     */
    public function edit()
    {
        $user = Auth::user();
        $estudante =$user->estudante()
            ->with([
                'curso.departamento', 
                'tutorRelacao.tutor.user',   // Estudante -> EstudanteTutor -> Professor (Tutor) -> User
                'tutorRelacao.coTutor.user' // Estudante -> EstudanteTutor -> Professor (Co-Tutor) -> User
            ])
            ->first();// Carrega a relação um-para-um

        if (!$estudante) {
            return redirect()->back()->with('error', 'Perfil de estudante não localizado.');
        }

        return view('app.private.dashboard.estudantes.perfil.perfil', compact('user', 'estudante'));
    }

    /**
     * Atualiza os dados cadastrais e académicos do estudante.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $estudante = $user->estudante;

        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bi' => ['required', 'string', 'max:20', Rule::unique('estudantes')->ignore($estudante->id)],
            'ano_frequencia' => ['required', 'integer', 'min:1', 'max:6'],
            'regime' => ['required', Rule::in(['Regular', 'Pós Laboral'])],
        ]);

        try {
            DB::transaction(function () use ($request, $user, $estudante) {
                // 1. Atualiza dados da tabela 'users'
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                // 2. Atualiza dados da tabela 'estudantes' (mantendo curso_id e numero_matricula protegidos)
                $estudante->update([
                    'bi' => $request->bi,
                    'ano_frequencia' => $request->ano_frequencia,
                    'regime' => $request->regime,
                ]);
            });

            return redirect()->route('profile.estudante.edit')->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar as alterações.');
        }
    }

    /**
     * Modifica a senha de acesso de forma isolada.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            Auth::user()->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('profile.estudante.edit')->with('success', 'Senha alterada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar a senha.');
        }
    }
}