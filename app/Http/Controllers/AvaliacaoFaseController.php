<?php
 
namespace App\Http\Controllers;

use App\Models\FaseTcc;
use App\Models\AvaliacaoFase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoFaseController extends Controller
{
    public function store(Request $request, $faseId)
    {
        $request->validate([
            'status' => 'required|in:aprovado,ajustes,pendente',
            'descricao' => 'required|string|min:5',
        ]);

        $fase = FaseTcc::findOrFail($faseId);
        $submissao = $fase->submissao;

        if (!$submissao) {
            return redirect()->back()->with('error', 'Não há submissão para avaliar.');
        }

        // 1. Atualiza o status na tabela fases_tcc
        $fase->update([
            'status' => $request->status
        ]);

        // 2. Se a decisão for aprovado ou ajustes, grava/atualiza a tabela avaliacao_fases
        if (in_array($request->status, ['aprovado', 'ajustes'])) {
            AvaliacaoFase::updateOrCreate(
                ['submissao_fase_id' => $submissao->id],
                [
                    'tutor_id' => Auth::user()->tutor->id, // Ajuste conforme seu Auth
                    'decisao'  => $request->status,
                    'descricao' => $request->descricao,
                ]
            );
        }

        return redirect()->back()->with('success', 'Avaliação publicada com sucesso!');
    }
}