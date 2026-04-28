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
        $campanha = Campanha::withCount('anteprojetos')->findOrFail($campanha_id);
    
    // Pegamos todos os anteprojetos da campanha e carregamos apenas a última versão de cada
    $anteprojetos = Anteprojeto::where('campanha_id', $campanha_id)
        ->with(['estudante.user', 'versoes' => function($q) {
            $q->latest();
        }])
        ->get();

    return view('app.private.dashboard.othes.anteprojetos.index', compact('campanha', 'anteprojetos'));

       // return view('app.private.dashboard.othes.anteprojetos.index', compact('campanha', 'anteprojetos'));
    }
public function historicoAnteprojeto($id)
{
    $anteprojeto = Anteprojeto::with([
        'estudante.user',
        'versoes.decisao.coordenador.professor.user' // Carrega a decisão de cada versão
    ])->findOrFail($id);

    return view('app.private.dashboard.othes.anteprojetos.historico', compact('anteprojeto'));
}
    public function show($id)
{
    // Carrega o anteprojeto com a relação da campanha para o cabeçalho
    $anteprojeto = Anteprojeto::with('campanha')->findOrFail($id);

    // Opcional: Validar se o usuário logado tem permissão (ex: se é o dono ou coordenador)
    // if (Auth::user()->estudante->id !== $anteprojeto->estudante_id) { abort(403); }

    return view('app.private.dashboard.othes.anteprojetos.show', compact('anteprojeto'));
}
 public function storeAvaliacao(Request $request, $id)
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
