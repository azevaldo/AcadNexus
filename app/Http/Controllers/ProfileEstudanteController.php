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
        try{
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
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    /**
     * Atualiza os dados cadastrais e académicos do estudante.
     */
    public function update(Request $request)
    {
        try{
        $user = Auth::user();
        $estudante = $user->estudante;

      /*  $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bi' => ['required', 'string', 'max:20', Rule::unique('estudantes')->ignore($estudante->id)],
            'ano_frequencia' => ['required', 'integer', 'min:1', 'max:6'],
            'regime' => ['required', Rule::in(['Regular', 'Pós Laboral'])],
        ]);
*/
            $request->validate([
    // Nome válido (apenas letras e espaços)
   'name' => [
    'required',
    'string',
    'min:3',
    'max:100',
    'regex:/^[\p{L}\s]+$/u'
],
    
    // Email único, mas ignorando o ID do próprio usuário que está sendo editado
    'email' => [
        'required',
        'email',
        'unique:users,email,' . $estudante->user_id
    ],
    
    // BI validado (9 números + 2 letras + 3 números) ignorando o próprio registro
    'bi' => [
        'required',
        'string',
        'size:14',
        'regex:/^\d{9}[A-Z]{2}\d{3}$/i',
        'unique:estudantes,bi,' . $estudante->id
    ],
    
    // Ano de frequência restrito a 4 ou 5
    'ano_frequencia' => 'required|in:4,5',
    
    'curso_id' => 'required|exists:cursos,id',
    
    // Matrícula única, ignorando a matrícula atual deste estudante
    'numero_matricula' => [
        'required',
        'unique:estudantes,numero_matricula,' . $estudante->id
    ],
    
    'regime' => 'required|in:Regular,Pós Laboral'
], [
    // Mensagens de erro personalizadas amigáveis
    'name.regex' => 'O campo nome deve conter apenas letras e espaços.',
    'bi.regex' => 'O formato do BI é inválido. Deve conter 9 números, seguidos de 2 letras e 3 números (Ex: 000000000LA000).',
    'bi.size' => 'O BI deve ter exatamente 14 caracteres.',
    'ano_frequencia.in' => 'O ano de frequência deve ser obrigatoriamente o 4º ou 5º ano.'
]);

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
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar as alterações : '.$e->getMessage());
        }
    }

    /**
     * Modifica a senha de acesso de forma isolada.
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

            return redirect()->route('profile.estudante.edit')->with('success', 'Senha alterada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar a senha.');
        }
    }
}