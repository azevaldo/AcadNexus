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
     
   public function estudantePorCurso(Curso $curso=null, Request $request)
    {
        try{
        // Inicia a query isolando os estudantes deste curso específico
       
        $query = Estudante::where('curso_id', $curso->id)
            ->with(['user', 'curso', 'tutorRelacao.tutor.user', 'tutorRelacao.coTutor.user']);

        // 1. Filtro por Nome (Busca na tabela relacionada 'users')
        if ($request->filled('nome')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->nome . '%');
            });
        }

        // 2. Filtro por Email (Busca na tabela relacionada 'users')
        if ($request->filled('email')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'LIKE', '%' . $request->email . '%');
            });
        }

        // 3. Filtro de Regime Académico
        if ($request->filled('regime')) {
            $query->where('regime', $request->regime);
        }

        // 4. Filtro de Ano de Frequência
        if ($request->filled('ano')) {
            $query->where('ano_frequencia', $request->ano);
        }

        // 5. Filtro de Orientação Avançada (Tutor Principal)
        if ($request->filled('tutor_status')) {
            if ($request->tutor_status === 'com_tutor') {
                $query->whereHas('tutorRelacao', function ($q) {
                    $q->whereNotNull('tutor_id')->where('ativo', true);
                });
            } elseif ($request->tutor_status === 'sem_tutor') {
                $query->whereDoesntHave('tutorRelacao', function ($q) {
                    $q->whereNotNull('tutor_id')->where('ativo', true);
                });
            }
        }

        // 6. Filtro de Co-orientação Avançada (Co-Tutor)
        if ($request->filled('cotutor_status')) {
            if ($request->cotutor_status === 'com_cotutor') {
                $query->whereHas('tutorRelacao', function ($q) {
                    $q->whereNotNull('co_tutor_id')->where('ativo', true);
                });
            } elseif ($request->cotutor_status === 'sem_cotutor') {
                $query->whereDoesntHave('tutorRelacao', function ($q) {
                    $q->whereNotNull('co_tutor_id')->where('ativo', true);
                });
            }
        }

        // Executa a paginação mantendo a integridade dos dados
        $estudantes = $query->paginate(10);
        $departamento = $curso->departamento;

        return view('app.private.dashboard.othes.cursos.estudantes', [
            'estudantes' => $estudantes,
            'curso' => $curso,
            'departamento' => $departamento
        ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function index()
    {
        try {
            $this->authorize('admin_or_secret');

            $cursos = Curso::with('departamento')->paginate(10);
            $departamentos = Departamento::orderBy('nome')->get();

            return view(
                'app.private.dashboard.othes.cursos.index',
                compact('cursos', 'departamentos')
            );

        } catch (Exception $e) {
            Log::error('Erro cursos index: '.$e->getMessage());
            return redirect()->route('dashboard')
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
            return redirect()->back()
                ->with('success', 'Curso criado com sucesso');

        } catch (Exception $e) {
           // dd($e->getMessage());
            DB::rollBack();
            //Log::error('Erro ao criar curso: '.$e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao criar curso : '.$e->getMessage());
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

            return redirect()->back()
                ->with('success', 'Curso eliminado com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao eliminar curso: '.$e->getMessage());
            return redirect()->route('cursos.index')
                ->with('error', 'Erro ao eliminar curso'.$e->getMessage());
        }
    }
}