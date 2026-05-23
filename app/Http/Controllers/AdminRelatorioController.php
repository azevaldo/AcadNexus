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

class AdminRelatorioController extends Controller
{
    public function index()
    {
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
    }
}