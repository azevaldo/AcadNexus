<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DecisaoAnteprojeto;
use App\Models\Anteprojeto;
use App\Models\AnteprojetoVersao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnteprojetoAvaliacaoController extends Controller
{
    public function destroy(DecisaoAnteprojeto $decisao)
{
    try{
    $versao = $decisao->versao;
    $anteprojeto = $versao->anteprojeto;
    $campanha = $anteprojeto->campanha;
    $hoje = Carbon::today();

    // 1. CONDICIONAL DE PRAZOS DA CAMPANHA
    $prazoSubmissaoValido = $campanha->prazo_submissao && Carbon::parse($campanha->prazo_submissao)->startOfDay()->gte($hoje);
    $prazoMelhoriasValido = $campanha->prazo_melhorias && Carbon::parse($campanha->prazo_melhorias)->startOfDay()->gte($hoje);

    if (!$prazoSubmissaoValido && !$prazoMelhoriasValido) {
        return redirect()->back()->with('error', 'Não é possível eliminar a decisão. Os prazos desta campanha já expiraram.');
    }

    // 2. CONDICIONAL MULTI-ANTEPROJETOS (Isolado por Campanha E por Estudante)
    // Busca apenas os anteprojetos deste MESMO estudante nesta MESMA campanha
    $anteprojetosDoEstudante = Anteprojeto::where('campanha_id', $campanha->id)
        ->where('estudante_id', $anteprojeto->estudante_id) // 🔥 Correção aqui
        ->orderBy('id', 'asc')
        ->pluck('id')
        ->toArray();

    $totalAnteprojetos = count($anteprojetosDoEstudante);

    if ($totalAnteprojetos >= 2) {
        // Se o estudante tem mais de um anteprojeto na campanha, bloqueia exclusões no primeiro dele
        if ($anteprojeto->id === $anteprojetosDoEstudante[0]) {
            return redirect()->back()->with('error', 'Ação bloqueada. Não é permitido eliminar decisões do primeiro anteprojeto deste estudante nesta campanha.');
        }
    }

    // 3. CONDICIONAL DE VERSÕES DO MESMO ANTEPROJETO
    $versoesDoAnteprojeto = AnteprojetoVersao::where('anteprojeto_id', $anteprojeto->id)
        ->orderBy('numero_versao', 'asc')
        ->pluck('id')
        ->toArray();

    $totalVersoes = count($versoesDoAnteprojeto);

    if ($totalVersoes >= 2) {
        // Não permite apagar o parecer da V1 se o projeto já possui novas versões (V2, V3...)
        if ($versao->id === $versoesDoAnteprojeto[0]) {
            return redirect()->back()->with('error', 'Negação de histórico. Não é permitido excluir o parecer da primeira versão se já existem revisões posteriores.');
        }
    }

    // EXECUÇÃO SEGURA DOS PROCEDIMENTOS DE REVERSÃO
    DB::transaction(function () use ($decisao, $versao, $anteprojeto) {
        $decisao->itensMelhoria()->detach();
        $decisao->delete();

        // Retorna os estados para o valor padrão de submissão
        $versao->update(['status' => 'submetido']);
        $anteprojeto->update(['status' => 'submetido']);
    });

    return redirect()->back()->with('success', 'Parecer técnico removido. O anteprojeto retornou ao estado "submetido".');
} catch (\Exception $e) {
        return redirect()->back()->with('error', 'Ocorreu um erro : ' . $e->getMessage());
    }
    }
}