<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Estudante;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    public function __construct()
    {
      //  $this->middleware('roles:admin')->only(['destroy']);
    }
    public function estudantePorCursoCreate(Curso $curso){
            return view('app.private.dashboard.othes.cursos.estudanteCreate', compact( 'curso'));
    }
    public function estudantePorCurso(Curso $curso)
{

    // Busca estudantes vinculados ao curso específico
    $estudantes = Estudante::where('curso_id', $curso->id)
        ->with(['user', 'curso'])
        ->paginate(10); // Usando paginação conforme sua view sugere

    return view('app.private.dashboard.othes.cursos.estudantes', [
        'estudantes' => $estudantes,
        'curso' => $curso // Passamos o curso para o cabeçalho dinâmico
    ]);
}
    public function index()
    {
        try {
         //   $this->authorize('admin_or_secret');

            $cursos = Curso::with('departamento')->paginate(10);
            $departamentos = Departamento::orderBy('nome')->get();

            return view(
                'app.private.dashboard.othes.cursos.index',
                compact('cursos', 'departamentos')
            );

        } catch (Exception $e) {
            Log::error('Erro cursos index: '.$e->getMessage());
            return redirect()->route('dashboard1')
                ->with('error', 'Erro ao carregar cursos'.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
           // $this->authorize('admin_or_secret');

            $request->validate([
                'departamento_id' => ['required', 'exists:departamentos,id'],
                'nome' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/u',
                    'unique:cursos,nome'
                ],
            ]);

            DB::beginTransaction();

            Curso::create([
                'departamento_id' => $request->departamento_id,
                'nome' => $request->nome
            ]);

            DB::commit();
            return redirect()->route('cursos.index')
                ->with('success', 'Curso criado com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar curso: '.$e->getMessage());
            return redirect()->route('cursos.index')
                ->with('error', 'Erro ao criar curso'.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
           // $this->authorize('admin_or_secret');

            $request->validate([
                'departamento_id' => ['required', 'exists:departamentos,id'],
                'nome' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/u',
                    "unique:cursos,nome,$id"
                ],
            ]);

            DB::beginTransaction();

            $curso = Curso::findOrFail($id);
            $curso->update([
                'departamento_id' => $request->departamento_id,
                'nome' => $request->nome
            ]);

            DB::commit();
            return redirect()->route('cursos.index')
                ->with('success', 'Curso atualizado com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar curso: '.$e->getMessage());
            return redirect()->route('cursos.index')
                ->with('error', 'Erro ao atualizar curso'.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
          //  $this->authorize('admin');

            DB::beginTransaction();
            Curso::findOrFail($id)->delete();
            DB::commit();

            return redirect()->route('cursos.index')
                ->with('success', 'Curso eliminado com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao eliminar curso: '.$e->getMessage());
            return redirect()->route('cursos.index')
                ->with('error', 'Erro ao eliminar curso'.$e->getMessage());
        }
    }
}