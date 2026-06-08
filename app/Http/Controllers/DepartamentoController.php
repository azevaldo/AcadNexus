<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Professor;
use App\Models\Coordenador;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    public function __construct()
    { 
       $this->middleware('roles:admin,secretaria,')->only(['destroy','store']);
    }
public function cursos(Departamento $departamento,$privado=null)
{
    try{
   // dd("o q foi?");
    // Busca professores que possuem o departamento_id na tabela intermediária
    $cursos = $departamento->cursos()->paginate(10);
    $caminho=$privado?route('professor.departamentos'):route('departamentos.index');
    return view('app.private.dashboard.othes.cursos.index', [
        'cursos' => $cursos,
        'departamento' => $departamento ,// Passamos o departamento para o cabeçalho
        'caminho'=>$caminho,
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
        $user = Auth::user();

        // 1. Verificação de segurança: Se for Admin, traz absolutamente tudo
        if ($user->hasRole('admin')) {
            $departamentos = Departamento::paginate(10);
            return view('app.private.dashboard.othes.departamentos.index', compact('departamentos'));
        }

        // 2. Se NÃO for Admin, mas tiver a role de coordenador (ou secretaria/professor com acesso)
        if ($user->hasAnyRole(['coordenador', 'secretaria'])) {
            
            // Busca o perfil de professor ligado ao usuário logado
            $professor = Professor::where('user_id', $user->id)->first();

            if (!$professor) {
                return redirect()->route('dashboard1')->with('error', 'Perfil de professor não encontrado para este usuário.');
            }

            // IDs dos departamentos onde ele é o COORDENADOR ATIVO
            $idsDepartamentosCoordenados = Coordenador::where('professor_id', $professor->id)
                ->where('ativo', true) // ou 1, dependendo do seu banco
                ->pluck('departamento_id')
                ->toArray();

            // IDs dos departamentos onde ele está vinculado apenas como PROFESSOR comum
            $idsDepartamentosVinculados = $professor->departamentos()->pluck('departamentos.id')->toArray();

            // Mescla todos os IDs e remove duplicados para não quebrar a listagem
            $todosIdsDepartamentos = array_unique(array_merge($idsDepartamentosCoordenados, $idsDepartamentosVinculados));

            // Busca os departamentos finais mantendo a paginação correta do Laravel
            $departamentos = Departamento::whereIn('id', $todosIdsDepartamentos)->paginate(10);

            return view('app.private.dashboard.othes.departamentos.index', compact('departamentos'));
        }

        // 3. Bloqueio caso caia aqui sem as roles necessárias
        return redirect()->route('dashboard1')->with('error', 'Você não tem permissão para acessar esta página.');

    } catch (Exception $e) {
        Log::error("Erro departamentos index: " . $e->getMessage());
        return redirect()->route('dashboard1')->with('error', 'Erro ao carregar departamentos: ' . $e->getMessage());
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