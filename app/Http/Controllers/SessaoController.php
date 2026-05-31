<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <--- Adicione esta linha
use App\Models\AcompanhamentoTcc;
use App\Models\Anteprojeto;
use App\Models\Campanha;
use App\Models\User;
use App\Models\Curso;
use App\Models\Estudante;
use Carbon\Carbon;
class SessaoController extends Controller
{
    //
    public function dashboard(){
        try{
    $user=Auth::user();    
    if($user->hasRole('estudante')){
      // $user = Auth::user();

    // Buscamos o estudante com TODAS as suas relações
    $estudante = Estudante::with([
        'curso.departamento',
        'campanhas', 
        'anteprojetos.campanha',
        'tutorRelacao.tutor.user',
        'tutorRelacao.coTutor.user',
        
        // 🔥 CORRIGIDO: De 'submissao' para 'submissoes' mapeando a árvore até a avaliação
        'acompanhamento.fases.submissoes.avaliacao'
    ])
    ->where('user_id', $user->id)
    ->firstOrFail();

    // Facilitamos o acesso ao acompanhamento (pode ser null)
    $acompanhamento = $estudante->acompanhamento;

   return view('app.private.dashboard.estudantes.dashboard', compact('estudante', 'acompanhamento'));
 
        // 2. Se o estudante ainda não tiver um acompanhamento iniciado
        
        return view('app.private.dashboard.estudantes.dashboard',compact('acompanhamento'));
    } else if ($user->hasRole('professor') || $user->hasRole('coordenador')) {
    $user = Auth::user();
    
    // Pegar o ID do registro na tabela 'professores' vinculado ao usuário logado
    // (Pois nas tabelas de relacionamento você usa o ID do professor, não o ID do user)
    $professorId = \App\Models\Professor::where('user_id', $user->id)->value('id');

    // --- ESTATÍSTICAS ---
    $queryAcompanhamento = AcompanhamentoTcc::query();
    
    if ($user->hasRole('professor') && !$user->hasRole('coordenador')) {
        // Ajuste aqui caso a tabela acompanhamentos use o professor_id ou tutor_id da relação
        $queryAcompanhamento->where('tutor_id', $professorId);
    }

    // Contagem de Anteprojetos Pendentes com tratamento de escopo correto
    $queryAnteprojetos = Anteprojeto::where('status', 'pendente');

    // Se for apenas professor, filtra os anteprojetos que pertencem aos alunos dele (tutor ou co-tutor)
    if ($user->hasRole('professor') && !$user->hasRole('coordenador')) {
        $queryAnteprojetos->whereHas('estudante.tutorRelacao', function ($q) use ($professorId) {
            $q->where(function($sub) use ($professorId) {
                $sub->where('tutor_id', $professorId)
                    ->orWhere('co_tutor_id', $professorId);
            })->where('ativo', true);
        });
    }
    $pendentesCount = $queryAnteprojetos->count();

    $stats = [
        'tccs_em_curso'       => $queryAcompanhamento->count(),
        'pendentes_avaliacao' => $pendentesCount,
        'campanhas_ativas'    => Campanha::where('status', 'aberta')->count(),
    ];

    // --- LISTAGEM PRINCIPAL ---
    if ($user->hasRole('coordenador')) {
        $recentes = Anteprojeto::with(['estudante.user'])->latest()->take(6)->get();
    } else {
        // Traz os anteprojetos recentes dos estudantes vinculados a este professor
        $recentes = Anteprojeto::with(['estudante.user'])
            ->whereHas('estudante.tutorRelacao', function ($q) use ($professorId) {
                $q->where(function($sub) use ($professorId) {
                    $sub->where('tutor_id', $professorId)
                        ->orWhere('co_tutor_id', $professorId);
                })->where('ativo', true);
            })
            ->latest()
            ->take(6)
            ->get();
    }

    // --- CAMPANHAS ---
    $campanhas = Campanha::where('status', 'aberta')
        ->orderBy('prazo_submissao', 'asc')
        ->take(3)
        ->get()
        ->map(function($camp) {
            $hoje = Carbon::now();
            $prazo = Carbon::parse($camp->prazo_submissao);
            $camp->dias_restantes = $hoje->diffInDays($prazo, false);
            
            $criacao = Carbon::parse($camp->created_at);
            $total = $criacao->diffInDays($prazo);
            $passado = $criacao->diffInDays($hoje);
            $camp->progresso = ($total > 0) ? min(100, max(0, ($passado / $total) * 100)) : 0;
            
            return $camp;
        });

    return view('app.private.dashboard.othes.dashboard', compact('stats', 'recentes', 'campanhas'));

                
        }else{
$stats = [
            'total_utilizadores' => User::count(),
            'total_cursos'       => Curso::count(),
            'total_projetos'     => Anteprojeto::count(),
            'campanhas_ativas'   => Campanha::where('status', 'aberta')->count(),
        ];

        // Distribuição de Usuários por Role (para o gráfico ou lista)
        // Se quiseres usar os modelos do Spatie diretamente
$usersByRole = \Spatie\Permission\Models\Role::withCount('users')->get();
// Nota: Para isto funcionar, a tua Model User deve ter o trait "use HasRoles;"

        // Últimos Usuários Registrados
        $recentUsers = User::with('roles')->latest()->take(8)->get();

        // Cursos com mais projetos (Visão analítica)
        $cursosPopulares = Curso::withCount('anteprojetos')
            ->orderBy('anteprojetos_count', 'desc')
            ->take(5)
            ->get();
        return view('app.private.dashboard.othes.dashboardAdmin', compact('stats', 'recentUsers', 'cursosPopulares', 'usersByRole'));
        }
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function home(){
        return view('app.home.index');
    }
}
