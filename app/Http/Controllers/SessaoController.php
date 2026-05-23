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
    $user=Auth::user();    
    if($user->hasRole('estudante')){
      // $user = Auth::user();

    // Buscamos o estudante com TODAS as suas relações
    $estudante = Estudante::with([
            'curso.departamento',
            'campanhas', // Campanhas em que está inscrito
            'anteprojetos.campanha',
            'tutorRelacao.tutor.user',
            'tutorRelacao.coTutor.user',
            'acompanhamento.fases.submissao.avaliacao'
        ])
        ->where('user_id', $user->id)
        ->firstOrFail();

    // Facilitamos o acesso ao acompanhamento (pode ser null)
    $acompanhamento = $estudante->acompanhamento;

   return view('app.private.dashboard.estudantes.dashboard', compact('estudante', 'acompanhamento'));
 
        // 2. Se o estudante ainda não tiver um acompanhamento iniciado
        
        return view('app.private.dashboard.estudantes.dashboard',compact('acompanhamento'));
    }else if(($user->hasRole('professor') )||($user->hasRole('coordenador')) ){
       $user = Auth::user();
        
        // --- ESTATÍSTICAS ---
        $queryAcompanhamento = AcompanhamentoTcc::query();
        $queryAnteprojetos = Anteprojeto::query();

        if ($user->hasRole('professor') && !$user->hasRole('coordenador')) {
            $queryAcompanhamento->where('tutor_id', $user->id);
            $queryAnteprojetos->where('tutor_id', $user->id);
        }

        $stats = [
            'tccs_em_curso' => $queryAcompanhamento->count(),
            'pendentes_avaliacao' => $queryAnteprojetos->where('status', 'pendente')->count(),
            'campanhas_ativas' => Campanha::where('status', 'aberta')->count(),
        ];

        // --- LISTAGEM PRINCIPAL ---
        $recentes = $user->hasRole('coordenador') 
            ? Anteprojeto::with(['estudante.user'])->latest()->take(6)->get()
            : Anteprojeto::with(['estudante.user'])->where('tutor_id', $user->id)->latest()->take(6)->get();

        // --- CAMPANHAS (Baseado na sua Migration) ---
        $campanhas = Campanha::where('status', 'aberta')
            ->orderBy('prazo_submissao', 'asc')
            ->take(3)
            ->get()
            ->map(function($camp) {
                $hoje = Carbon::now();
                $prazo = Carbon::parse($camp->prazo_submissao);
                $camp->dias_restantes = $hoje->diffInDays($prazo, false);
                
                // Cálculo de progresso visual
                $criacao = Carbon::parse($camp->created_at);
                $total = $criacao->diffInDays($prazo);
                $passado = $criacao->diffInDays($hoje);
                $camp->progresso = ($total > 0) ? min(100, max(0, ($passado / $total) * 100)) : 0;
                
                return $camp;
            });
       // return view('app.private.dashboard.index', compact('stats', 'recentes', 'campanhas'));

        return view('app.private.dashboard.othes.dashboard', compact('stats', 'recentes', 'campanhas'));
              //  return view('app.private.dashboard.othes.dashboard');
                
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
    }
    public function home(){
        return view('app.home.index');
    }
}
