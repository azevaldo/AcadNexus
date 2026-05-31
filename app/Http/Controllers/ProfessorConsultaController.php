<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;

class ProfessorConsultaController extends Controller
{
    /**
     * Filtra e lista os professores com base nos critérios enviados no request.
     */
    public function index(Request $request)
    {
        try{
       // dd("peguei");
        // Eager loading para evitar queries duplicadas no loop da tabela (Problema N+1)
        $query = Professor::with(['user', 'departamentos', 'areas', 'acompanhamentos']);

        // Filtro por Nome (tabela users)
        if ($request->filled('nome')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nome . '%');
            });
        }

        // Filtro por Email (tabela users)
        if ($request->filled('email')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        // Filtro por ID do Departamento
        if ($request->filled('departamento_id')) {
            $query->whereHas('departamentos', function ($q) use ($request) {
                $q->where('departamentos.id', $request->departamento_id);
            });
        }

        // Filtro por ID da Área Científica
        if ($request->filled('area_cientifica_id')) {
            $query->whereHas('areas', function ($q) use ($request) {
                $q->where('areas_cientificas.id', $request->area_cientifica_id);
            });
        }

        // Filtro por Status de Acesso (ativo/suspenso)
        if ($request->filled('status')) {
            $statusVal = $request->status === 'ativo' ? 1 : 0;
            $query->whereHas('user', function ($q) use ($statusVal) {
                $q->where('acesso', $statusVal);
            });
        }

        // Pagina os resultados mantendo os filtros ativos na paginação do Tailwind
        $professores = $query->paginate(10);

        return view('app.private.dashboard.othes.professores.consultas.index', compact('professores'));
        } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Ocorreu um erro : ' . $e->getMessage());
    }
    }

    /**
     * Lista todos os estudantes vinculados ao histórico de acompanhamento do professor.
     */
    public function estudantesAcompanhados($id)
    {
        // Busca o professor trazendo aninhados os registros de acompanhamentos, os estudantes e seus users
        $professor = Professor::with(['user', 'acompanhamentos.estudante.user'])->findOrFail($id);

        return view('app.private.dashboard.othes.professores.consultas.estudantes', compact('professor'));
    }
}