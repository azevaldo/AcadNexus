<?php

namespace App\Http\Controllers;

use App\Models\Campanha;
use App\Models\Anteprojeto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnteprojetoGestaoController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Query base trazendo os relacionamentos essenciais
            $query = Anteprojeto::with(['estudante.user', 'estudante.curso', 'campanha', 'versoes']);

            $campanha = null;

            // 1. Filtro por Campanha (Substituindo o antigo parâmetro de URL)
            if ($request->filled('campanha_id')) {
                $query->where('campanha_id', $request->campanha_id);
                // Carrega os dados da campanha filtrada com o contador original
                $campanha = Campanha::withCount('anteprojetos')->find($request->campanha_id);
            }

            // 2. Filtro por Título / Tema do Anteprojeto
            if ($request->filled('titulo')) {
                $query->where('titulo', 'LIKE', '%' . $request->titulo . '%');
            }

            // 3. Filtro por Nome do Estudante
            if ($request->filled('aluno_nome')) {
                $query->whereHas('estudante.user', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->aluno_nome . '%');
                });
            }

            // 4. Filtro por Status Global
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // 5. Filtro por Quantidade de Versões
            if ($request->filled('versao_count')) {
                $operator = $request->versao_count_operator === 'maior' ? '>' : '=';
                $query->has('versoes', $operator, $request->versao_count);
            }

            // 6. Filtro por Intervalo de Data de Envio da Última Versão
            if ($request->filled('data_inicio') && $request->filled('data_fim')) {
                $dataInicio = Carbon::parse($request->data_inicio)->startOfDay();
                $dataFim = Carbon::parse($request->data_fim)->endOfDay();

                $query->whereHas('ultimaVersao', function ($q) use ($dataInicio, $dataFim) {
                    $q->whereBetween('created_at', [$dataInicio, $dataFim]);
                });
            }

            // Ordenação e paginação dos resultados
            $anteprojetos = $query->latest('updated_at')->paginate(10);

            // Coleta todas as campanhas para preencher o select do Modal de Filtros
            $campanhasDisponiveis = Campanha::all();

            return view('app.private.dashboard.othes.anteprojetos.consultas.index', [
                'anteprojetos' => $anteprojetos,
                'campanha' => $campanha, // Pode ser null
                'campanhasDisponiveis' => $campanhasDisponiveis
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar anteprojetos: ' . $e->getMessage());
        }
    }
}