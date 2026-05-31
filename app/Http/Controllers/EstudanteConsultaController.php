<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudante;
use Illuminate\Http\Request;

class EstudanteConsultaController extends Controller
{
    public function estudantePorCurso(Request $request)
    {
        try {
            // Começa com a query base trazendo as relações da tabela
            $query = Estudante::with(['user', 'curso', 'tutorRelacao.tutor.user', 'tutorRelacao.coTutor.user']);

            // Inicializa as variáveis como null para a View não quebrar no primeiro carregamento
            $curso = null;
            $departamento = null;

            // 1. Filtro por Curso (Se o usuário selecionar um curso no modal)
            if ($request->filled('curso_id')) {
                $query->where('curso_id', $request->curso_id);
                $curso = Curso::find($request->curso_id);
                $departamento = $curso ? $curso->departamento : null;
            }

            // 2. Filtro por Nome
            if ($request->filled('nome')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->nome . '%');
                });
            }

            // 3. Filtro por Email
            if ($request->filled('email')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('email', 'LIKE', '%' . $request->email . '%');
                });
            }

            // 4. Filtro de Regime Académico
            if ($request->filled('regime')) {
                $query->where('regime', $request->regime);
            }

            // 5. Filtro de Ano de Frequência
            if ($request->filled('ano')) {
                $query->where('ano_frequencia', $request->ano);
            }

            // 6. Filtro de Tutor Principal
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

            // 7. Filtro de Co-Tutor
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

            // Paginação dos resultados (traz tudo se não houver filtro, ou filtra se houver)
            $estudantes = $query->paginate(10);

            // Carrega todos os cursos para popular o <select> do modal
            $cursosDisponiveis = Curso::all(); 

            return view('app.private.dashboard.othes.estudantes.consultas.index', [
                'estudantes' => $estudantes,
                'curso' => $curso, // Pode ser null ou o modelo do curso filtrado
                'departamento' => $departamento, // Pode ser null ou o modelo do departamento
                'cursosDisponiveis' => $cursosDisponiveis
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    /**
     * Controles de alteração de acesso (Botão Bloquear/Ativar da tabela)
     */

     
}