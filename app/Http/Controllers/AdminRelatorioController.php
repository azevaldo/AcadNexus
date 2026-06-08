<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Estudante;
use App\Models\Professor;
use App\Models\Departamento;
use App\Models\Anteprojeto;
use App\Models\Campanha;
use App\Models\EstudanteTutor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminRelatorioController extends Controller
{
    public function index()
    {
        try{
        // 1. Estatísticas Globais (Big Numbers)
        $stats = [
            'total_usuarios'   => User::count(),
            'total_estudantes' => Estudante::count(),
            'total_professores' => Professor::count(),
            'total_anteprojetos' => Anteprojeto::count(),
            'estudantes_com_tutor' => EstudanteTutor::distinct('estudante_id')->count(),
        ];

        // Calcular % de cobertura de orientação
        $stats['taxa_orientacao'] = $stats['total_estudantes'] > 0 
            ? round(($stats['estudantes_com_tutor'] / $stats['total_estudantes']) * 100, 1) 
            : 0;

        // 2. Distribuição de Estudantes por Departamento
        $departamentos_stats = Departamento::withCount('estudantes')
            ->orderBy('estudantes_count', 'desc')
            ->get();

        // 3. Últimos Anteprojetos Submetidos (Geral)
        $ultimos_anteprojetos = Anteprojeto::with(['estudante.user', 'campanha'])
            ->latest()
            ->take(8)
            ->get();

        // 4. Ranking de Professores com mais orientações (Top 10 da Instituição)
        $top_orientadores = Professor::with('user')
            ->withCount(['orientacoes as total'])
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        return view('app.private.dashboard.othes.admin.relatorios.index', compact(
            'stats', 
            'departamentos_stats', 
            'ultimos_anteprojetos', 
            'top_orientadores'
        ));
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function usuarios(Request $request){
        $query = User::with('roles');
try{
            // 2. Filtros Dinâmicos (Só rodam se houver dados enviados pelo formulário do modal)
            if ($request->filled('name')) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->filled('email')) {
                $query->where('email', 'LIKE', '%' . $request->email . '%');
            }

            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }

            // 3. CRUCIAL: Ordena por data decrescente e força a PAGINAÇÃO (corrige o erro do ->total())
            $users = $query->latest('created_at')->paginate(15);

            // 4. Busca os papéis para alimentar o select do modal
            $rolesDisponiveis = Role::all(); 
//dd("ola");
            // 5. Retorna para a view correta indicada no seu exemplo
            return view('app.private.dashboard.othes.admin.usuarios', [
                'recentUsers' => $users,
                'rolesDisponiveis' => $rolesDisponiveis
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar lista de utilizadores: ' . $e->getMessage());
        }
    }
}