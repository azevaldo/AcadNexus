<?php

namespace App\Http\Controllers;

use App\Models\AcompanhamentoTcc;
use App\Models\Anteprojeto;
use App\Models\AvaliacaoFase;
use App\Models\SubmissaoFase;
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
     public function inicio(Estudante $estudante)
{
    try{
    $tutor = Auth::user()->professor;

    // 1. Verifica o vínculo na tabela estudante_tutores
    $vinculo = EstudanteTutor::where('estudante_id', $estudante->id)
        ->where(function($q) use ($tutor) {
            $q->where('tutor_id', $tutor->id)
              ->orWhere('co_tutor_id', $tutor->id);
        })->firstOrFail();

    // 2. Busca automaticamente o último anteprojeto aprovado
    $anteprojeto = Anteprojeto::where('estudante_id', $estudante->id)
        ->where('status', 'aprovado')
        ->latest()
        ->first();

    if (!$anteprojeto) {
        return redirect()->back()->with('error', 'O estudante não possui um anteprojeto aprovado para iniciar o acompanhamento.');
    }

    // 3. Verifica se o estudante já possui um acompanhamento para evitar duplicados
    if ($estudante->acompanhamento) {
        return redirect()->back()->with('error', 'O estudante já tem um acompanhamento iniciado.');
    }

    // RETORNO DA VIEW: Apontando exatamente para o arquivo que você mandou
    return view('app.private.dashboard.othes.tutores.acompanhamento.inicio', compact('estudante', 'vinculo', 'anteprojeto'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
public function storeBasic(Request $request, Estudante $estudante)
    {
        try {
            $tutor = Auth::user()->professor;

            // Evitar duplicados
            if ($estudante->acompanhamento) {
                return redirect()->back()->with('error', 'Este estudante já possui um acompanhamento iniciado.');
            }

            $acompanhamento = AcompanhamentoTcc::create([
                'estudante_id'   => $estudante->id,
                'tutor_id'       => $request->tutor_id,
                'co_tutor_id'    => $request->co_tutor_id,
                'anteprojeto_id' => $request->anteprojeto_id,
                'status'         => 'ativo', // Status inicial antes do cronograma
                'mes_referencia' => null // Fica vazio por enquanto
            ]);

            return redirect()->route('tutor.estudantes.index')
                ->with('success', 'Acompanhamento iniciado! Agora você pode configurar o cronograma quando desejar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao iniciar: ' . $e->getMessage());
        }
    }

    // PASSO 2: View para Criar as Fases (A que você enviou, adaptada)
    public function createFases(AcompanhamentoTcc $acompanhamento)
    {
        try{
        if(!$acompanhamento->fases->isEmpty()){
            return back()->with('error','Cronograma já foi criado');
        }
        $acompanhamento->load('estudante.user', 'estudante.anteprojetos');
            $tutor = $acompanhamento->tutor;
$estudante=Estudante::findOrFail($acompanhamento->estudante_id);
    // 1. Verifica o vínculo na tabela estudante_tutores
    $vinculo = EstudanteTutor::where('estudante_id', $acompanhamento->estudante->id)
        ->where(function($q) use ($tutor) {
            $q->where('tutor_id', $tutor->id)
              ->orWhere('co_tutor_id', $tutor->id);
        })->firstOrFail();

    // 2. Busca automaticamente o último anteprojeto aprovado
    $anteprojeto = Anteprojeto::where('estudante_id', $acompanhamento->estudante->id)
        ->where('status', 'aprovado')
        ->latest()
        ->first();

    

   

    return view('app.private.dashboard.othes.tutores.acompanhamento.create', compact('estudante', 'vinculo', 'anteprojeto','acompanhamento'));

     //   return view('app.private.dashboard.othes.tutores.acompanhamento.configurar_fases', compact('acompanhamento'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
  

    // PASSO 3: Salvar o Cronograma
    public function storeFases(Request $request, AcompanhamentoTcc $acompanhamento)
    {
        try{
        $request->validate([
            'mes_referencia' => 'required',
            'fases.*.fase'   => 'required|string',
            'fases.*.mes_inicio' => 'required|integer',
            'fases.*.mes_fim'    => 'required|integer',
        ]);

        
            DB::transaction(function () use ($request, $acompanhamento) {
                // Atualiza o mês de referência e ativa o processo
                $acompanhamento->update([
                    'mes_referencia' => $request->mes_referencia . '-01',
                    'status'         => 'ativo'
                ]);

                // Cria as fases
                foreach ($request->fases as $fData) {
                    $acompanhamento->fases()->create([
                        'fase'       => $fData['fase'],
                        'descricao'  => $fData['descricao'] ?? null,
                        'mes_inicio' => $fData['mes_inicio'],
                        'mes_fim'    => $fData['mes_fim'],
                        'status'     => 'pendente'
                    ]);
                }
            });
            if(Auth::user()->hasRole('estudante'))
return redirect()->route('estudante.acompanhamento.show', $acompanhamento->id)
                ->with('success', 'Cronograma configurado com sucesso!');
                else
return redirect()->route('tutor.acompanhamento.show', $acompanhamento->id)
                ->with('success', 'Cronograma configurado com sucesso!');

            

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao salvar fases: ' . $e->getMessage());
        }
    }
    public function index()
    {
        //
    }
    
    
    public function show(?AcompanhamentoTcc $acompanhamento = null)
{
    // 1. Defesa preventiva: Se o acompanhamento não existir ou for nulo, aborta com 404
    if (!$acompanhamento || !$acompanhamento->exists) {
        abort(404, 'Acompanhamento de TCC não encontrado.');
    }

    try {
        // 2. Executa a query em lote de visualização apenas para as fases DESTE acompanhamento
        \App\Models\SubmissaoFase::whereIn('fase_tcc_id', $acompanhamento->fases()->pluck('id'))
            ->where('visto', false)
            ->whereIn('id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                      ->from('submissao_fase')
                      ->groupBy('fase_tcc_id');
            })
            ->update(['visto' => true]);

        // 3. Carrega os relacionamentos em cascata com segurança
        $acompanhamento->load([
            'estudante.user',
            'fases' => function($query) {
                $query->with(['submissoes' => function($q) {
                    $q->latest()->with('avaliacao');
                }]);
            }
        ]);

        return view('app.private.dashboard.othes.tutores.acompanhamento.show', compact('acompanhamento'));

    } catch (\Exception $e) {
        // Se houver qualquer falha estrutural (ex: tabela de fases vazia), não quebra a tela do tutor
        return redirect()->back()->with('error', 'Erro ao processar visualização: ' . $e->getMessage());
    }
}
// Altere o tipo recebido de SubmissaoFase para apenas a variável $id
public function avaliarFase(Request $request, $id)
{
    try{
    $request->validate([
        'status' => 'required|in:aprovado,ajustes,pendente',
        'descricao' => 'required|string|min:5',
    ]);

    // Busca manualmente a submissão com seus relacionamentos no banco
    $submissao = SubmissaoFase::with('fase.acompanhamento')->findOrFail($id);

 $tutor = Auth::user()->professor;
    $fase = $submissao->fase;

    if (!$fase || !$fase->acompanhamento) {
        return redirect()->back()->with('error', 'Dados de acompanhamento não localizados.');
    }

    if ($fase->acompanhamento->tutor_id !== $tutor->id && $fase->acompanhamento->co_tutor_id !== $tutor->id) {
        return redirect()->back()->with('error', 'Você não tem permissão para avaliar esta fase.');
    }
 
        DB::transaction(function () use ($request, $fase, $submissao, $tutor) {
            // 2. Atualiza a fase com o status correto ('aprovado' ou 'ajustes')
            $fase->update([
                'status' => $request->status
            ]);

            // 3. Registra o parecer respeitando o ENUM do banco
            AvaliacaoFase::updateOrCreate(
                ['submissao_fase_id' => $submissao->id],
                [
                    'tutor_id' => $tutor->id,
                    'decisao' => $request->status,
                    'descricao' => $request->descricao
                ]
            );

            // 4. Se tudo foi aprovado, encerra o fluxo geral
            $totalFases = $fase->acompanhamento->fases()->count();
            $fasesAprovadas = $fase->acompanhamento->fases()->where('status', 'aprovado')->count();

            if ($totalFases === $fasesAprovadas) {
                $fase->acompanhamento->update(['status' => 'finalizado']);
            }
        });

        return redirect()->back()->with('success', 'Avaliação registrada com sucesso!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao salvar avaliação: ' . $e->getMessage());
    }
}

    public function destroyAvaliacao(AvaliacaoFase $avaliacao)
    {
        try{
        $tutor = Auth::user()->professor;
        $fase = $avaliacao->submissao->fase;

        if ($fase->acompanhamento->tutor_id !== $tutor->id && $fase->acompanhamento->co_tutor_id !== $tutor->id) {
            return redirect()->back()->with('error', 'Você não tem permissão para remover esta avaliação.');
        }
 
            DB::transaction(function () use ($avaliacao, $fase) {
                // Remove o registro da avaliação
                $avaliacao->delete();

                // Faz a fase retornar para o estado de análise padrão de envio
                $fase->update([
                    'status' => 'submetido'
                ]);

                // Se o acompanhamento estava finalizado, reabre-o como ativo
                if ($fase->acompanhamento->status === 'finalizado') {
                    $fase->acompanhamento->update(['status' => 'ativo']);
                }
            });

            return redirect()->back()->with('success', 'Avaliação removida! O status retornou para submetido.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao remover avaliação: ' . $e->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     */public function create(Estudante $estudante)
{
    try{
    $tutor = Auth::user()->professor;

    // 1. Verifica o vínculo na tabela estudante_tutores
    $vinculo = EstudanteTutor::where('estudante_id', $estudante->id)
        ->where(function($q) use ($tutor) {
            $q->where('tutor_id', $tutor->id)
              ->orWhere('co_tutor_id', $tutor->id);
        })->firstOrFail();

    // 2. Busca automaticamente o último anteprojeto aprovado
    $anteprojeto = Anteprojeto::where('estudante_id', $estudante->id)
        ->where('status', 'aprovado')
        ->latest()
        ->first();

    if (!$anteprojeto) {
        return redirect()->back()->with('error', 'O estudante não possui um anteprojeto aprovado para iniciar o acompanhamento.');
    }

    // 3. CORREÇÃO: Adicionado o $ e verificação de existência
    // Verificamos se o acompanhamento existe E se o tutor_id é diferente do tutor atual
    if ($estudante->acompanhamento && $estudante->acompanhamento->tutor_id != $tutor->id) {
        return redirect()->back()->with('error', 'O estudante já tem um acompanhamento com outro tutor.');
    }

    return view('app.private.dashboard.othes.tutores.acompanhamento.create', compact('estudante', 'vinculo', 'anteprojeto'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function store(Request $request, Estudante $estudante)
{
    try{
    $request->validate([
        'mes_referencia' => 'required', // Formato YYYY-MM do input month
        'fases.*.fase'   => 'required|string',
        'fases.*.mes_inicio' => 'required|integer|min:1',
        'fases.*.mes_fim'    => 'required|integer|gte:fases.*.mes_inicio',
    ]);

   
        return DB::transaction(function () use ($request, $estudante) {
            // 1. Criar o Acompanhamento com a Âncora Temporal
            $acompanhamento = AcompanhamentoTcc::create([
                'estudante_id'   => $estudante->id,
                'tutor_id'       => $request->tutor_id,
                'co_tutor_id'    => $request->co_tutor_id,
                'anteprojeto_id' => $request->anteprojeto_id,
                'mes_referencia' => $request->mes_referencia . '-01', // Normaliza para data
                'status'         => 'ativo'
            ]);

            // 2. Criar as Fases com meses relativos
            foreach ($request->fases as $faseData) {
                $acompanhamento->fases()->create([
                    'fase'       => $faseData['fase'],
                    'descricao'  => $faseData['descricao'] ?? null,
                    'mes_inicio' => $faseData['mes_inicio'],
                    'mes_fim'    => $faseData['mes_fim'],
                    'status'     => 'ativo'
                ]);
            }

            return redirect()->route('tutor.estudantes.index')->with('success', 'Plano de acompanhamento criado!');
        });
    } catch (\Exception $e) {
        Log::error('Erro ao criar acompanhamento: ' . $e->getMessage());
        return back()->with('error', 'Erro ao salvar o cronograma: ' . $e->getMessage())->withInput();
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
    try{
   $acompanhamento->load('fases', 'estudante.user', 'tutor.user');

    // Mapeamos as fases para o formato que o Alpine.js espera (usando meses relativos)
    $fasesEdit = $acompanhamento->fases->map(function($f) {
        return [
            'id' => $f->id,
            'fase' => $f->fase,
            'descricao' => $f->descricao,
            'mes_inicio' => $f->mes_inicio, // Supondo que você guarde o número do mês
            'mes_fim' => $f->mes_fim,
        ];
    })->toArray(); 
     

    return view('app.private.dashboard.othes.tutores.acompanhamento.edit', compact('acompanhamento', 'fasesEdit'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

public function update(Request $request, AcompanhamentoTcc $acompanhamento)
{
    try{
    $request->validate([
        'mes_referencia' => 'required',
        'fases' => 'required|array|min:1',
        'fases.*.fase' => 'required|string|max:255',
        'fases.*.mes_inicio' => 'required|integer|min:1',
        'fases.*.mes_fim' => 'required|integer|gte:fases.*.mes_inicio',
    ]);

    
        DB::transaction(function () use ($request, $acompanhamento) {
            // 1. Atualizar o mês de referência caso tenha mudado
            $acompanhamento->update([
                'mes_referencia' => $request->mes_referencia . '-01'
            ]);
//dd( $acompanhamento->mes_referencia);
            $fasesEnviadas = collect($request->fases);
            $idsEnviados = $fasesEnviadas->pluck('id')->filter()->toArray();

            // 2. Eliminar fases que foram deletadas pelo usuário na interface do Alpine
            $acompanhamento->fases()->whereNotIn('id', $idsEnviados)->delete();

            // 3. Atualizar existentes ou criar as novas adicionadas
            foreach ($request->fases as $dadosFase) {
                $acompanhamento->fases()->updateOrCreate(
                    ['id' => $dadosFase['id'] ?? null], // Se tiver ID atualiza, se for null cria
                    [
                        'fase' => $dadosFase['fase'],
                        'descricao' => $dadosFase['descricao'] ?? null,
                        'mes_inicio' => $dadosFase['mes_inicio'],
                        'mes_fim' => $dadosFase['mes_fim'],
                        // 'status' => 'pendente' // Opcional: descomente se quiser resetar o status ao editar
                    ]
                );
            }
        });
 if(Auth::user()->hasRole('estudante'))
return redirect()->route('estudante.acompanhamento.show', $acompanhamento->id)
                ->with('success', 'Cronograma atualizado com sucesso!');
                else
return redirect()->route('tutor.acompanhamento.show', $acompanhamento->id)
                ->with('success', 'Cronograma atualizado com sucesso!');

       
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao atualizar o cronograma: ' . $e->getMessage());
    }
}
public function update2(Request $request, AcompanhamentoTcc $acompanhamento)
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
