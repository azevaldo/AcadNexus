<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileAdminController extends Controller
{
    /**
     * Exibe o formulário de edição do perfil do administrador.
     */
    public function edit()
    {
        try{
        $user = Auth::user();
        return view('app.private.dashboard.othes.admin.perfil.perfil', compact('user'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    /**
     * Atualiza as informações básicas de identificação do administrador.
     */
    public function update(Request $request)
    {

    try{
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
             'telefone' => ['required'],
        ]);

       
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'telefone' => $request->telefone,
            ]);

            return redirect()->route('profile.admin.edit')->with('success', 'Perfil de administrador atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar os seus dados.');
        }
    }

    /**
     * Altera de forma isolada a senha master do administrador.
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

            return redirect()->route('profile.admin.edit')->with('success', 'Senha do administrador alterada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar a senha administrativa.');
        }
    }
}