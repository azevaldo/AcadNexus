<?php

namespace App\Http\Controllers;

use App\Models\AcompanhamentoTcc;
use App\Models\FaseTcc;
use App\Models\SubmissaoFase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EstudanteAcompanhamentoController extends Controller
{
    //
    // Lista os acompanhamentos do estudante logado
    public function index()
    {
        $estudante = Auth::user()->estudante;
        $acompanhamentos = AcompanhamentoTcc::where('estudante_id', $estudante->id)
            ->with(['tutor.user'])
            ->latest()
            ->get();

        return view('app.private.dashboard.estudantes.acompanhamentos.index', compact('acompanhamentos'));
    }

    // Visualiza detalhes, fases e permite submissão
    public function show(AcompanhamentoTcc $acompanhamento)
    {
        // Garante que o estudante só veja o seu próprio acompanhamento
        if ($acompanhamento->estudante_id !== Auth::user()->estudante->id) {
            abort(403);
        }

        $acompanhamento->load(['fases.submissao.avaliacao', 'tutor.user']);

        return view('app.private.dashboard.estudantes.acompanhamentos.show', compact('acompanhamento'));
    }

    // Processa o upload da submissão
    public function storeSubmissao2(Request $request, FaseTcc $fase)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:pdf,docx|max:10240',
            'comentario_estudante' => 'nullable|string'
        ]);

        $path = $request->file('arquivo')->store('submissoes_tcc');

        // Atualiza ou cria a submissão para esta fase
        SubmissaoFase::updateOrCreate(
            ['fase_tcc_id' => $fase->id],
            [
                'arquivo' => $path,
                'descricao_estudante' => $request->comentario_estudante,
                
            ]
        );

        // Opcional: Mudar status da fase para 'submetido'
        $fase->update(['status' => 'pendente']);

        return redirect()->back()->with('success', 'Documento enviado com sucesso para análise!');
    }
    public function storeSubmissao(Request $request, $faseId)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:pdf,docx|max:10240',
            'descricao_estudante' => 'required|string|max:1000',
        ]);

        $fase = FaseTcc::findOrFail($faseId);

        // Se já existir uma submissão, deletamos o arquivo antigo do storage
        if ($fase->submissao) {
            Storage::disk('public')->delete($fase->submissao->arquivo);
        }

        // Salva o novo arquivo
        $path = $request->file('arquivo')->store('submissoes', 'public');

        // Atualiza ou Cria o registro no banco
        $fase->submissao()->updateOrCreate(
            ['fase_tcc_id' => $fase->id],
            [
                'arquivo' => $path,
                'descricao_estudante' => $request->descricao_estudante,
            ]
        );

        // Volta o status para pendente para o professor reavaliar
        $fase->update(['status' => 'pendente']);

        return redirect()->back()->with('success', 'Submissão enviada com sucesso!');
    }

    public function destroy($id)
    {
        $submissao = SubmissaoFase::findOrFail($id);
        $fase = $submissao->fase; // Assume que existe o relacionamento belongsTo

        // Deleta o arquivo físico
        if (Storage::disk('public')->exists($submissao->arquivo)) {
            Storage::disk('public')->delete($submissao->arquivo);
        }

        $submissao->delete();

        // Opcional: Voltar status da fase para pendente sem envio
        $fase->update(['status' => 'pendente']);

        return redirect()->back()->with('success', 'Submissão removida.');
    }
}
