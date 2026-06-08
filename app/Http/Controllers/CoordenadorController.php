<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\Departamento;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CoordenadorController extends Controller
{
    public function index()
    {
        try{
        $coordenadores = Coordenador::with(['departamento','professor.user'])->where('ativo',true)
            ->paginate(10);

        $departamentos = Departamento::all();
        $professores = Professor::all();

        return view('app.private.dashboard.othes.coordenadores.index', compact(
            'coordenadores',
            'departamentos',
            'professores'
        ));
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

 public function store(Request $request)
{
    $request->validate([
        'departamento_id' => 'required|exists:departamentos,id',
        'professor_id' => 'required|exists:professores,id',
    ]);

    try {
        DB::beginTransaction();

        // 1. 🔒 Verifica se o departamento JÁ possui um coordenador diferente ativo
        $departamentoTem = Coordenador::where('departamento_id', $request->departamento_id)
            ->where('professor_id', '!=', $request->professor_id)
            ->where('ativo', true)
            ->exists();

        if ($departamentoTem) {
            return back()->with('error', 'Este departamento já possui um coordenador ativo.');
        }

        // 2. 🔒 Verifica se o professor já é coordenador ativo de OUTRO departamento
        $professorJa = Coordenador::where('professor_id', $request->professor_id)
            ->where('departamento_id', '!=', $request->departamento_id)
            ->where('ativo', true)
            ->exists();

        if ($professorJa) {
            return back()->with('error', 'Este professor já é coordenador de outro departamento.');
        }

        // 3. 🔄 Busca se já existe um histórico (ativo ou inativo) deste professor NESTE departamento
        $coordenadorExistente = Coordenador::where('departamento_id', $request->departamento_id)
            ->where('professor_id', $request->professor_id)
            ->first();

        if ($coordenadorExistente) {
            // Se já existia e estava inativo, apenas reativa
            $coordenadorExistente->update(['ativo' => true]);
            $coordenador = $coordenadorExistente;
        } else {
            // Se nunca existiu registro deles juntos, cria um novo
            $coordenador = Coordenador::create([
                'departamento_id' => $request->departamento_id,
                'professor_id' => $request->professor_id,
                'ativo' => true
            ]);
        }

        // 🏫 NOVO: Garante que o professor faça parte do corpo docente deste departamento
        // syncWithoutDetaching evita duplicar se ele já estiver associado
        $professor = $coordenador->professor;
        $professor->departamentos()->syncWithoutDetaching([$request->departamento_id]);

        // 🏅 Atribui a role de coordenador ao usuário vinculado ao professor
        $user = $professor->user;
        if ($user && !$user->hasRole('coordenador')) {
            $user->assignRole('coordenador');
        }

        DB::commit();

        return redirect()->back()
            ->with('success', 'Coordenador atribuído e vinculado ao departamento com sucesso.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error($e->getMessage());

        return back()->with('error','Erro ao criar coordenador.');
    }
}

public function destroy(Coordenador $coordenador)
{
    try {
        DB::beginTransaction();

        // 1. 🔄 Em vez de deletar fisicamente, apenas desativa o registro
        $coordenador->update(['ativo' => false]);

        // 2. 🛡️ Verifica se o professor coordena OUTRO departamento ativo antes de remover a role
        $coordenadorEmOutroLugar = Coordenador::where('professor_id', $coordenador->professor_id)
            ->where('ativo', true)
            ->exists();

        if (!$coordenadorEmOutroLugar) {
            $user = $coordenador->professor->user;
            if ($user) {
                // Remove a role de coordenador do sistema de permissões
                $user->removeRole('coordenador');
            }
        }

        DB::commit();

        return back()->with('success', 'Coordenador desassociado com sucesso e marcado como inativo.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error($e->getMessage());

        return back()->with('error', 'Erro ao desassociar coordenador: ' . $e->getMessage());
    }
}
}