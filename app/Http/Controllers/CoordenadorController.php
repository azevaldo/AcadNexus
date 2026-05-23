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
        $coordenadores = Coordenador::with(['departamento','professor.user'])
            ->paginate(10);

        $departamentos = Departamento::all();
        $professores = Professor::all();

        return view('app.private.dashboard.othes.coordenadores.index', compact(
            'coordenadores',
            'departamentos',
            'professores'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'professor_id' => 'required|exists:professores,id',
        ]);

        DB::beginTransaction();

        try {

            // 🔒 Verifica se o departamento já tem coordenador ativo
            $departamentoTem = Coordenador::where('departamento_id', $request->departamento_id)
                ->where('ativo', true)
                ->exists();

            if ($departamentoTem) {
                return back()->with('error','Este departamento já possui coordenador ativo.');
            }

            // 🔒 Verifica se o professor já é coordenador
            $professorJa = Coordenador::where('professor_id', $request->professor_id)
                ->where('ativo', true)
                ->exists();

            if ($professorJa) {
                return back()->with('error','Este professor já é coordenador de outro departamento.');
            }

            $coordenador=Coordenador::create([
                'departamento_id' => $request->departamento_id,
                'professor_id' => $request->professor_id,
                'ativo' => true
            ]);
            $coordenador->professor->user-> assignRole('coordenador');
 
            DB::commit();

            return redirect()->route('coordenadores.index')
                ->with('success', 'Coordenador atribuído com sucesso.');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return back()->withErrors('Erro ao criar coordenador.');
        }
    }

    public function destroy(Coordenador $coordenador)
    {
        DB::beginTransaction();

        try {

            $coordenador->delete();

            DB::commit();

            return back()->with('success','Coordenador removido.');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return back()->withErrors('Erro ao remover coordenador.');
        }
    }
}