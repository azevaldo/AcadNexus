<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Professor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartamentoController extends Controller
{
    public function __construct()
    { 
       $this->middleware('roles:admin,secretaria,')->only(['destroy','store']);
    }
public function cursos(Departamento $departamento)
{
    try{
   // dd("o q foi?");
    // Busca professores que possuem o departamento_id na tabela intermediária
    $cursos = $departamento->cursos()->paginate(10);
    return view('app.private.dashboard.othes.cursos.index', [
        'cursos' => $cursos,
        'departamento' => $departamento // Passamos o departamento para o cabeçalho
    ]);
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}
    public function ProfessorPorDepartamento(Departamento $departamento)
{
    try{
    // Busca professores que possuem o departamento_id na tabela intermediária
    $professores = Professor::whereHas('departamentos', function($query) use ($departamento) {
        $query->where('departamento_id', $departamento->id);
    })->with(['user', 'areas', 'departamentos'])->paginate(10);

    return view('app.private.dashboard.othes.departamentos.professores', [
        'professores' => $professores,
        'departamento' => $departamento // Passamos o departamento para o cabeçalho
    ]);
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}
    public function index()
    {
        try {
         //   $this->authorize('admin_or_secret');

            $departamentos = Departamento::paginate(10);
             
            return view('app.private.dashboard.othes.departamentos.index', compact('departamentos'));

        } catch (Exception $e) {
            Log::error("Erro departamentos index: ".$e->getMessage());
            return redirect()->route('dashboard1')->with('error', 'Erro ao carregar departamentos'.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
          //  $this->authorize('admin_or_secret');

            $request->validate([
                'nome' => [
                    'required',
                    'string',
                    'min:5',
                    'max:100',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/u',
                    'unique:departamentos,nome'
                ],
            ]);

            DB::beginTransaction();

            Departamento::create([
                'nome' => $request->nome
            ]);

            DB::commit();
            return redirect()->route('departamentos.index')->with('success', 'Departamento criado com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao criar departamento: ".$e->getMessage());
            return redirect()->route('departamentos.index')->with('error', 'Erro ao criar departamento'.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
          //  $this->authorize('admin_or_secret');

            $request->validate([
                'nome' => [
                    'required',
                    'string',
                    'min:5',
                    'max:100',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/u',
                    "unique:departamentos,nome,$id"
                ],
            ]);

            DB::beginTransaction();

            $departamento = Departamento::findOrFail($id);
            $departamento->update([
                'nome' => $request->nome
            ]);

            DB::commit();
            return redirect()->route('departamentos.index')->with('success', 'Departamento atualizado com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar departamento: ".$e->getMessage());
            return redirect()->route('departamentos.index')->with('error', 'Erro ao atualizar departamento'.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
          //  $this->authorize('admin');

            DB::beginTransaction();
            Departamento::findOrFail($id)->delete();
            DB::commit();

            return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao eliminar departamento: ".$e->getMessage());
            return redirect()->route('departamentos.index')->with('error', 'Erro ao eliminar departamento'.$e->getMessage());
        }
    }
}