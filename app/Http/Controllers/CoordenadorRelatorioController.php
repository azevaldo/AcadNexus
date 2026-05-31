<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\Estudante;
use App\Models\Anteprojeto;
use App\Models\Campanha;
use App\Models\Professor;
use App\Models\EstudanteTutor;
use Illuminate\Support\Facades\Auth;

class CoordenadorRelatorioController extends Controller
{
    public function index()
    {
        try{
        // 1. Identificar o Coordenador logado
        $coordenador = Coordenador::whereHas('professor', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('ativo', true)
            ->firstOrFail();

        $cursosIds = $coordenador->departamento->cursos->pluck('id');

        // 2. Estatísticas Gerais
        $stats = [
            'total_estudantes' => Estudante::whereIn('curso_id', $cursosIds)->count(),
            
            'total_campanhas'  => Campanha::whereIn('curso_id', $cursosIds)->count(),
            
            'anteprojetos'     => Anteprojeto::whereHas('campanha', function($q) use ($cursosIds) {
                                    $q->whereIn('curso_id', $cursosIds);
                                 })->count(),
            
            // CORREÇÃO: Usando 'tutorRelacao' que é o método definido no seu Model Estudante
            'estudantes_sem_tutor' => Estudante::whereIn('curso_id', $cursosIds)
                                    ->whereDoesntHave('tutorRelacao') 
                                    ->count(),
        ];

        // 3. Listagem de Campanhas Ativas
        $campanhasAtivas = Campanha::withCount(['anteprojetos', 'estudantes'])
            ->whereIn('curso_id', $cursosIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Professores do Departamento e carga (Orientadores)
        // Aqui buscamos a contagem através da tabela estudante_tutores
        $professores = Professor::whereHas('departamentos', function($q) use ($coordenador) {
                $q->where('departamentos.id', $coordenador->departamento_id);
            })
            ->withCount(['orientacoes as total_orientandos']) 
            ->get();

        return view('app.private.dashboard.othes.coordenadores.relatorios.index', compact(
            'coordenador', 
            'stats', 
            'campanhasAtivas', 
            'professores'
        ));
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
}