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
    public function index()
    {
        try{
        $estudante = Auth::user()->estudante;
        $acompanhamentos = AcompanhamentoTcc::where('estudante_id', $estudante->id)
            ->with(['tutor.user'])
            ->latest()
            ->get();

        return view('app.private.dashboard.estudantes.acompanhamentos.index', compact('acompanhamentos'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    public function show(AcompanhamentoTcc $acompanhamento)
    {
        try{
        if ($acompanhamento->estudante_id !== Auth::user()->estudante->id) {
            abort(403);
        }

        // 🌟 Carrega as fases trazendo TODAS as submissões ordenadas e suas avaliações
        $acompanhamento->load(['fases' => function($query) {
            $query->with(['submissoes.avaliacao', 'submissoes' => function($q) {
                $q->latest();
            }]);
        }, 'tutor.user', 'coTutor.user']);

        return view('app.private.dashboard.estudantes.acompanhamentos.show', compact('acompanhamento'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    // 🌟 Processa uma NOVA submissão (Cria histórico, nunca sobrescreve)
    public function storeSubmissao(Request $request, $faseId)
    {
        try{
        $request->validate([
            'arquivo' => 'required|file|mimes:pdf,docx,zip,rar|max:51200', // Máx 50MB ajustado para segurança padrão
            'descricao_estudante' => 'required|string|max:1000',
        ]);

        $fase = FaseTcc::findOrFail($faseId);

        // Garante que o estudante é dono do acompanhamento desta fase
        if ($fase->acompanhamento->estudante_id !== Auth::user()->estudante->id) {
            abort(403);
        }

        // Salva o arquivo gerando um hash único no storage privado
        $path = $request->file('arquivo')->store('submissoes_tcc', 'public');

        // Cria um NOVO registro de submissão vinculando à fase
        $fase->submissoes()->create([
            'arquivo' => $path,
            'descricao_estudante' => $request->descricao_estudante,
        ]);

        // Modifica o status da fase para 'pendente' aguardando avaliação do tutor
        $fase->update(['status' => 'pendente']);

        return redirect()->back()->with('success', 'Nova versão da submissão enviada com sucesso!');
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    public function destroy($id)
    {
        try{
        $submissao = SubmissaoFase::findOrFail($id);
        $fase = $submissao->fase;

        // Proteção: Verifica se a submissão pertence ao estudante logado
        if ($fase->acompanhamento->estudante_id !== Auth::user()->estudante->id) {
            abort(403);
        }

        // Deleta o arquivo físico do disco
        if (Storage::disk('public')->exists($submissao->arquivo)) {
            Storage::disk('public')->delete($submissao->arquivo);
        }

        $submissao->delete();

        // Se após apagar não restarem mais submissões, podemos resetar o status da fase
        if ($fase->submissoes()->count() == 0) {
            $fase->update(['status' => 'em_aberto']); // ou o seu status padrão inicial
        }

        return redirect()->back()->with('success', 'Submissão removida do histórico com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
}