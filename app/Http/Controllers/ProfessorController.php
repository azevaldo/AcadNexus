<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Professor;
use App\Models\Departamento;
use App\Models\AreaCientifica;
use App\Models\Campanha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
     public function minhasCamapanhas()
{
    try {
        $user = Auth::user();
        
        // Carrega o professor e os departamentos aos quais ele pertence
        $professor = $user->professor;

        if (!$professor) {
            return back()->with('error', 'Perfil de professor não encontrado.');
        }

        // 1. Pegamos os IDs de todos os departamentos do professor
        $departamentosIds = $professor->departamentos->pluck('id');

        // 2. Buscamos as campanhas cujos cursos pertençam a esses departamentos
        // Usamos whereHas para filtrar pela relação curso -> departamento
        $campanhas = Campanha::whereHas('curso', function($query) use ($departamentosIds) {
            $query->whereIn('departamento_id', $departamentosIds);
        })
        ->with(['curso.departamento']) // Carregamento adiantado para a View
        ->latest()
        ->paginate(10);

        return view('app.private.dashboard.othes.professores.minhasCampanhas', compact('campanhas'));

    } catch (\Exception $e) {
        Log::error('Erro ao buscar campanhas do departamento: ' . $e->getMessage());
        return back()->with('error', 'Erro ao carregar campanhas: ' . $e->getMessage());
    }
}
    public function index()
    {
        try {

            $professores = Professor::with(['user','departamentos','areas'])
                ->latest()
                ->get();

            return view(
                'app.private.dashboard.othes.professores.index',
                compact('professores')
            );

        } catch (Exception $e) {

            Log::error('Erro ao listar professores: '.$e->getMessage());

            return back()->with('error','Erro ao carregar professores : '.$e->getMessage());
        }
    }

    public function create()
    {
        try {

            return view(
                'app.private.dashboard.othes.professores.create',
                [
                    'departamentos' => Departamento::orderBy('nome')->get(),
                    'areas' => AreaCientifica::orderBy('nome')->get()
                ]
            );

        } catch (Exception $e) {

            Log::error('Erro ao carregar tela de criação professor: '.$e->getMessage());

            return back()->with('error','Erro ao abrir formulário : '.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','email','unique:users,email'],
                'password' => ['required','confirmed','min:8'],
                'departamentos' => ['required','array','min:1'],
                'areas' => ['required','array','min:1'],
            ]);
 
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $professor = Professor::create([
                'user_id' => $user->id
            ]);

            $professor->departamentos()->sync($request->departamentos);
            $professor->areas()->sync($request->areas);
$user-> assignRole('professor');
 
            DB::commit();

            return redirect()
                ->route('departamentos.index')
                ->with('success','Professor criado com sucesso.');

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Erro ao criar professor: '.$e->getMessage());
            
            dd($e->getMessage());
            return back()
                ->with('error','Erro ao criar professor : '.$e->getMessage())
                ->withInput();
        }
    }

    public function edit(Professor $professor)
    {
        try {

            return view(
                'app.private.dashboard.othes.professores.edit',
                [
                    'professor' => $professor,
                    'departamentos' => Departamento::orderBy('nome')->get(),
                    'areas' => AreaCientifica::orderBy('nome')->get()
                ]
            );

        } catch (Exception $e) {

            Log::error('Erro ao editar professor: '.$e->getMessage());

            return back()->with('error','Erro ao abrir edição : '.$e->getMessage());
        }
    }

    public function update(Request $request, Professor $professor)
    {
        try {

            $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','email','unique:users,email,'.$professor->user_id],
                'departamentos' => ['required','array','min:1'],
                'areas' => ['required','array','min:1'],
            ]);

            DB::beginTransaction();

            $professor->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $professor->departamentos()->sync($request->departamentos);
            $professor->areas()->sync($request->areas);

            DB::commit();

            return redirect()
                ->route('professores.index')
                ->with('success','Professor atualizado com sucesso.');

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Erro ao atualizar professor: '.$e->getMessage());

            return back()
                ->with('error','Erro ao atualizar professor : '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Professor $professor)
    {
        try {

            DB::beginTransaction();

            // apagar usuário (cascade apaga professor)
            $professor->user()->delete();

            DB::commit();

            return back()->with('success','Professor eliminado com sucesso.');

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Erro ao eliminar professor: '.$e->getMessage());

            return back()->with('error','Erro ao eliminar professor : '.$e->getMessage() );
        }
    }
}