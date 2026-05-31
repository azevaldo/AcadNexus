<?php

namespace App\Http\Controllers;
use App\Models\Anteprojeto;
use App\Models\Campanha;
use Illuminate\Http\Request;
use App\Models\DecisaoAnteprojeto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class AnteprojetoAdminController extends Controller
{
    //
    public function index($campanha_id)
    {
        try{
        $campanha = Campanha::withCount('anteprojetos')->findOrFail($campanha_id);
    
    // Pegamos todos os anteprojetos da campanha e carregamos apenas a última versão de cada
    $anteprojetos = Anteprojeto::where('campanha_id', $campanha_id)
        ->with(['estudante.user', 'versoes' => function($q) {
            $q->latest();
        }])
        ->paginate(10);

    return view('app.private.dashboard.othes.anteprojetos.index', compact('campanha', 'anteprojetos'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
       // return view('app.private.dashboard.othes.anteprojetos.index', compact('campanha', 'anteprojetos'));
    }
public function historicoAnteprojeto($id)
{
    try{
    $anteprojeto = Anteprojeto::with([
        'estudante.user',
        'versoes.decisao.coordenador.professor.user' // Carrega a decisão de cada versão
    ])->findOrFail($id);
$itensMelhoria = \App\Models\ItemMelhoria::get();
if(!$anteprojeto->ultimaVersao->lida){
    $ultima=$anteprojeto->ultimaVersao;
    $ultima->lida=true;
$ultima->save();
    }
  
    return view('app.private.dashboard.othes.anteprojetos.historico', compact('itensMelhoria','anteprojeto'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function show($id)
{
    try{
    // Carrega o anteprojeto com a relação da campanha para o cabeçalho
    $anteprojeto = Anteprojeto::with('campanha')->findOrFail($id);
  
    // Opcional: Validar se o usuário logado tem permissão (ex: se é o dono ou coordenador)
    // if (Auth::user()->estudante->id !== $anteprojeto->estudante_id) { abort(403); }

    return view('app.private.dashboard.othes.anteprojetos.show', compact('anteprojeto'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

// Método para salvar a avaliação
public function storeAvaliacao(Request $request, $id) {
    try{
    $request->validate([
        'anteprojeto_versao_id' => 'required|exists:anteprojeto_versoes,id',
        'decisao' => 'required|in:aprovado,melhorias,rejeitado',
        'mensagem' => 'required|string',
        'melhorias' => 'nullable|array', // Array de IDs dos itens selecionados
    ]);

 
        DB::beginTransaction();
$anteprojeto = Anteprojeto::findOrFail($id);
$coordenador=$anteprojeto->campanha->curso->departamento->coordenador;
        // 1. Criar ou atualizar a decisão (assumindo modelo Decisao)
        $decisao = \App\Models\DecisaoAnteprojeto::updateOrCreate(
            ['anteprojeto_versao_id' => $request->anteprojeto_versao_id],
            [
                'decisao' => $request->decisao,
                'mensagem' => $request->mensagem,
                'coordenador_id' => $coordenador->id // Ajuste conforme seu auth
            ]
        );

        // 2. Sincronizar os itens de melhoria na tabela pivô
        // Se a decisão for 'melhorias', anexa os IDs; caso contrário, limpa.
        if ($request->decisao === 'melhorias' && $request->has('melhorias')) {
            // Relacionamento Many-to-Many entre Decisao e ItemMelhoria
            $decisao->itensMelhoria()->sync($request->melhorias);
        } else {
            $decisao->itensMelhoria()->detach();
        }

        // 3. Atualizar status do anteprojeto e da versão
        $anteprojeto = Anteprojeto::findOrFail($id);
        $anteprojeto->update(['status' => $request->decisao]);
        
        \App\Models\AnteprojetoVersao::where('id', $request->anteprojeto_versao_id)
            ->update(['status' => $request->decisao]);

        DB::commit();
        return redirect()->back()->with('success', 'Avaliação registrada com sucesso!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erro ao salvar: ' . $e->getMessage());
    }
}
 public function storeAvaliacaoOld(Request $request, $id)
{ 
    try {
    // 1. Validação robusta
    $request->validate([
        'anteprojeto_versao_id' => 'required|exists:anteprojeto_versoes,id',
        'decisao'               => 'required|in:aprovado,rejeitado,melhorias',
        'mensagem'              => 'required|string|min:10|max:2000',
    ]);

    // 2. Localizar o Anteprojeto
    $anteprojeto = Anteprojeto::findOrFail($id);

   
        // 3. Iniciar Transação para garantir consistência
        DB::transaction(function () use ($request, $anteprojeto) {
            
            // Criar o registro na tabela 'decisoes_anteprojeto'
            // Vinculado à versão enviada pelo modal
            \App\Models\DecisaoAnteprojeto::create([
                'anteprojeto_versao_id' => $request->anteprojeto_versao_id,
                'coordenador_id'        => 3//auth()->user()->professor->coordenador->id, // Ajuste conforme seu Auth
                ,'decisao'               => $request->decisao,
                'mensagem'              => $request->mensagem,
            ]);

            // 4. Sincronizar os status
            // Atualiza o status da Versão específica
            \App\Models\AnteprojetoVersao::where('id', $request->anteprojeto_versao_id)
                ->update(['status' => $request->decisao]);

            // Atualiza o status Global do Anteprojeto
            $anteprojeto->update([
                'status' => $request->decisao
            ]);
        });

        return back()->with('success', 'Parecer técnico registrado e enviado ao estudante!');

    } catch (\Exception $e) {
        // Caso algo dê errado (ex: erro de banco), volta com erro amigável
        return back()->with('error' ,'Falha ao registrar avaliação. Tente novamente.'.$e->getMessage());
    }
}
public function storeDecisao(Request $request, $id)
{
    try{
$request->validate([
        'decisao' => 'required|in:aprovado,rejeitado,melhorias',
        'mensagem' => 'required|string|min:10',
    ]);

    $anteprojeto = Anteprojeto::findOrFail($id);
    //dd($anteprojeto->campanha->coordenador);
   // dd($anteprojeto->campanha()->curso->departamento->coordenador_id);
   // $coordenador=$anteprojeto->campanha->curso->departamento->coordenador_id;
    
    DB::transaction(function () use ($request, $anteprojeto) {
        // 1. Criar o registro da decisão
       DecisaoAnteprojeto::create([
            'anteprojeto_id' => $anteprojeto->id,
            'coordenador_id' => $anteprojeto->campanha->coordenador_id, // Assume que o user logado é coordenador
            'decisao' => $request->decisao,
            'mensagem' => $request->mensagem,
        ]);

        // 2. Atualizar o status principal do anteprojeto
        $anteprojeto->update([
            'status' => $request->decisao
        ]);
    });

    return back()->with('success', 'Avaliação registada com sucesso!');

         } catch (Exception $e) {
            dd($e->getMessage());
            Log::error("Erro departamentos index: ".$e->getMessage());
            return redirect()->back()->with('error', 'Erro  : '.$e->getMessage());
        }
    }

}
