<?php

namespace App\Http\Controllers;

use App\Models\AcompanhamentoTcc;
use App\Models\Anteprojeto;
use App\Models\AvaliacaoFase;
use App\Models\Estudante;
use App\Models\EstudanteTutor;
use App\Models\FaseTcc;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcompanhamentoTccController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }public function avaliarFase(Request $request, FaseTcc $fase)
{
    // 1. Validação dos dados de entrada
    $request->validate([
        'status' => 'required|in:aprovado,ajustes,pendente',
        'descricao' => 'required|string|min:5',
    ]);

    // 2. Segurança: Verificar se o professor logado é realmente o tutor deste acompanhamento
    $tutor = Auth::user()->professor->tutor;
    if ($fase->acompanhamento->tutor_id !== $tutor->id && $fase->acompanhamento->co_tutor_id !== $tutor->id) {
        return redirect()->back()->with('error', 'Você não tem permissão para avaliar esta fase.');
    }

    try {
        DB::transaction(function () use ($request, $fase, $tutor) {
            // 3. Atualizar o status da Fase
            $fase->update([
                'status' => $request->status
            ]);

            // 4. Registrar ou Atualizar o Parecer na tabela avaliacao_fase
            // Usamos updateOrCreate para caso o tutor queira mudar uma avaliação já feita
            AvaliacaoFase::updateOrCreate(
                ['submissao_fase_id' => $fase->submissao->id], // Chave de busca
                [
                    'tutor_id' => $tutor->id,
                    'decisao' => $request->status,
                    'descricao' => $request->descricao
                ]
            );

            // 5. Lógica Extra: Se todas as fases forem 'aprovado', podemos finalizar o acompanhamento
            $totalFases = $fase->acompanhamento->fases()->count();
            $fasesAprovadas = $fase->acompanhamento->fases()->where('status', 'aprovado')->count();

            if ($totalFases === $fasesAprovadas) {
                $fase->acompanhamento->update(['status' => 'finalizado']);
            }
        });

        return redirect()->back()->with('success', 'Avaliação da fase registrada com sucesso!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao salvar avaliação: ' . $e->getMessage());
    }
}
public function show(AcompanhamentoTcc $acompanhamento)
{
   $acompanhamento->load([
        'estudante.user',
        'fases.submissao.avaliacao' // Carregamento em cascata: Fase -> Submissão -> Avaliação
    ]);
    return view('app.private.dashboard.othes.tutores.acompanhamento.show', compact('acompanhamento'));
}
    /**
     * Show the form for creating a new resource.
     */
  public function create(Estudante $estudante)
    {
        $tutor = Auth::user()->professor->tutor;

        // Verifica o vínculo na tabela estudante_tutores
        $vinculo = EstudanteTutor::where('estudante_id', $estudante->id)
            ->where(function($q) use ($tutor) {
                $q->where('tutor_id', $tutor->id)->orWhere('co_tutor_id', $tutor->id);
            })->firstOrFail();

        // Busca automaticamente o último anteprojeto aprovado
        $anteprojeto = Anteprojeto::where('estudante_id', $estudante->id)
            ->where('status', 'aprovado')
            ->latest()
            ->first();
 
        if (!$anteprojeto) {
            return redirect()->back()->with('error', 'O estudante não possui um anteprojeto aprovado para iniciar o acompanhamento.');
        }

        return view('app.private.dashboard.othes.tutores.acompanhamento.create', compact('estudante', 'vinculo', 'anteprojeto'));
    }

    public function store(Request $request, Estudante $estudante)
    {

    try{
        $request->validate([
            'fases.*.fase' => 'required|string',
            'fases.*.inicio' => 'required|date',
            'fases.*.fim' => 'required|date|after:fases.*.inicio',
        ]);

        DB::transaction(function () use ($request, $estudante) {
            // 1. Criar o Acompanhamento
            $acompanhamento = AcompanhamentoTcc::create([
                'estudante_id' => $estudante->id,
                'tutor_id' => $request->tutor_id,
                'co_tutor_id' => $request->co_tutor_id,
                'anteprojeto_id' => $request->anteprojeto_id,
                'status' => 'ativo'
            ]);

            // 2. Criar as Fases dinâmicas
            foreach ($request->fases as $faseData) {
                $acompanhamento->fases()->create([
                    'fase' => $faseData['fase'],
                    'descricao' => $faseData['descricao'],
                    'inicio' => $faseData['inicio'],
                    'fim' => $faseData['fim'],
                    'status' => 'pendente'
                ]);
            }
        });

        return redirect()->route('tutor.estudantes.index')->with('success', 'Plano de acompanhamento criado!');
      } catch (Exception $e) {

            DB::rollBack();
dd($e->getMessage());
            Log::error('Erro ao submeter anteprojeto: '.$e->getMessage());

            return back()->with('error','Erro ao submeter.');
        }
        }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(AcompanhamentoTcc $acompanhamento)
{
    $acompanhamento->load('fases', 'estudante.user');
    return view('app.private.dashboard.othes.tutores.acompanhamento.edit', compact('acompanhamento'));
}

public function update(Request $request, AcompanhamentoTcc $acompanhamento)
{
     try {
    $request->validate([
        'fases' => 'required|array|min:1',
        'fases.*.fase' => 'required|string|max:255',
        'fases.*.inicio' => 'required|date',
        'fases.*.fim' => 'required|date|after_or_equal:fases.*.inicio',
    ]);

   
        DB::transaction(function () use ($request, $acompanhamento) {
            $fasesEnviadas = collect($request->fases);
            $idsEnviados = $fasesEnviadas->pluck('id')->filter()->toArray();

            // 1. Eliminar fases que foram removidas na interface
            $acompanhamento->fases()->whereNotIn('id', $idsEnviados)->delete();

            // 2. Atualizar existentes ou Criar novas
            foreach ($request->fases as $dadosFase) {
                $acompanhamento->fases()->updateOrCreate(
                    ['id' => $dadosFase['id'] ?? null], // Busca pelo ID (se houver)
                    [
                        'fase' => $dadosFase['fase'],
                        'descricao' => $dadosFase['descricao'],
                        'inicio' => $dadosFase['inicio'],
                        'fim' => $dadosFase['fim'],
                        'status' => 'pendente' // Opcional: resetar status se editar?
                    ]
                );
            }
        });

        return redirect()->route('tutor.acompanhamento.show', $acompanhamento->id)
                         ->with('success', 'Cronograma atualizado com sucesso!');

    } catch (\Exception $e) {
        dd($e->getMessage());
        return redirect()->back()->with('error', 'Erro ao atualizar: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acompanhamento_tcc $acompanhamento_tcc)
    {
        //
    }
}
