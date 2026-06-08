<?php

namespace App\Http\Controllers;

use App\Models\Anteprojeto;
use App\Models\AnteprojetoVersao;
use App\Models\Campanha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnteprojetoController extends Controller
{
    public function destroy_versao($id)
    {
        try{
        $versao = AnteprojetoVersao::findOrFail($id);
        if($versao->lida){
  
        return redirect()->back()->with('error','Não é possivel apagar uma versão que ja foi lida');
    }
        // 1. Validar se pertence ao usuário (Segurança)
        if ($versao->anteprojeto->estudante->user_id !== auth()->id()) {
            return back()->with('error', 'Ação não autorizada.');
        }

        // 2. Opcional: Impedir apagar se for a última/única versão
       /* if ($versao->anteprojeto->versoes()->count() <= 1) {
            return back()->with('error', 'Não é possível eliminar a única versão do anteprojeto.');
        }*/

        // 3. Eliminar o arquivo físico
        if (Storage::exists('public/' . $versao->arquivo)) {
            Storage::delete('public/' . $versao->arquivo);
        }

        // 4. Eliminar registro no banco
        $versao->delete();
$ante=$versao->anteprojeto;
$ante ->status=$ante->ultimaVersao->status??'submetido';
$ante->save();
return back()->with('success', 'Versão eliminada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
// Exibe o formulário de NOVA VERSÃO (V2, V3...)
    public function novaVersao($id)
{
    try {
    // Carrega o anteprojeto com a campanha relacionada
    $anteprojeto = Anteprojeto::with('campanha')->findOrFail($id);
    $campanha = $anteprojeto->campanha;
    
    $hoje = Carbon::today();
               if($campanha->status!="aberta"){
                return redirect->back()->with('error','Esta campanha já não está aberta');
               } 
    // 1. Verificar se a campanha tem uma data de prazo_melhorias definida
    if (!$campanha->prazo_melhorias) {
        return redirect()->back()->with('error', 'Esta campanha não possui prazo de melhorias definido.');
    }

    // 2. Verificar se está dentro do prazo (Hoje <= Prazo)
    $prazoMelhorias = Carbon::parse($campanha->prazo_melhorias);
    if ($hoje->gt($prazoMelhorias)) {
        return redirect()->back()->with('error', 'O prazo para envio de melhorias já expirou.');
    }

    // 3. Verificar se o status do anteprojeto é 'melhorias'
    // Ajuste o termo 'melhorias' caso o status no banco seja diferente (ex: 'Pendente Correção')
    if ($anteprojeto->status !== 'melhorias') {
        return redirect()->back()->with('error', 'Você só pode enviar uma nova versão se o status atual for "melhorias".');
    }

    // Se passou por todas as validações, abre a view
    return view('app.private.dashboard.estudantes.anteprojetos.versao', compact('anteprojeto'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function updateTitulo(Request $request, $id)
    {
        try{
        $request->validate([
            'titulo' => 'required|string|max:255|min:3',
        ]);

        $anteprojeto = Anteprojeto::findOrFail($id);

        // Opcional: Impedir edição se já estiver aprovado
        if ($anteprojeto->status === 'aprovado') {
            return back()->with('error', 'Não é possível editar o título de um projeto já aprovado.');
        }

        $anteprojeto->update([
            'titulo' => $request->titulo
        ]);

        return back()->with('success', 'Título atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    // Armazena a nova versão
    public function storeVersao(Request $request, $id)
    {
         try {
        $request->validate([
            'descricao' => 'required|string|min:20',
            'arquivo'   => 'required|mimes:pdf,docx|max:256000',
        ]);

        $anteprojeto = Anteprojeto::findOrFail($id);

       
            DB::transaction(function () use ($request, $anteprojeto) {
                // 1. Upload do Arquivo
                $path = $request->file('arquivo')->store('anteprojetos/' . $anteprojeto->id, 'public');

                // 2. Criar a Versão (o número da versão é gerado pelo boot() na Model)
                $anteprojeto->versoes()->create([
                    'arquivo'   => $path,
                    'descricao' => $request->descricao,
                    'status'    => 'submetido'
                ]);

                // 3. Atualizar o status do Anteprojeto Pai para "em_analise" novamente
                $anteprojeto->update(['status' => 'submetido']);
            });

            return redirect()->route('estudante.anteprojetos.show',$anteprojeto->id)
                             ->with('success', 'Nova versão submetida com sucesso!');
       } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
public function show($id)
{
    try{
    // Carrega o anteprojeto com a relação da campanha para o cabeçalho
    $anteprojeto = Anteprojeto::with('campanha')->findOrFail($id);
    $campanha=$anteprojeto->campanha;
$estudante = Auth::user()->estudante;

        // Lista os anteprojetos do estudante nesta campanha, do mais recente para o mais antigo
        //$anteprojetos = Anteprojeto::where('campanha_id',$campanha->id)->where('estudante_id',$estudante->id)->orderBy('created_at','desc') ->get();
//dd($anteprojetos->first()->status);
            $hoje=Carbon::today();
            $prazoSubmissao=Carbon::parse($campanha->prazo_submissao);
            $prazoMelhorias=Carbon::parse($campanha->prazo_melhorias);
        
            $fase1=false;
                
                if($hoje->lt($prazoSubmissao)){
                    $fase1=true;
                }
            $fase2=false;
                if($campanha->prazo_melhorias!=null ){
                    if($hoje->lt($prazoMelhorias)){
                        $fase2=true;
                    }
                }else{
                    $fase2='Não definido';
                }
                $totalAnteprojetos = Anteprojeto::where('campanha_id', $campanha->id)
        ->where('estudante_id', $estudante->id)
        ->count();
        $totalVersoes=$anteprojeto->versoes()->count();
       // dd($totalVersoes);
    // Opcional: Validar se o usuário logado tem permissão (ex: se é o dono ou coordenador)
    // if (Auth::user()->estudante->id !== $anteprojeto->estudante_id) { abort(403); }
//dd($anteprojeto->decisoes);

    return view('app.private.dashboard.estudantes.anteprojetos.show', compact('totalVersoes','totalAnteprojetos','fase2','campanha','anteprojeto'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
public function meuAnteprojeto($campanha_id)
    {

    try{
        
        // Busca a campanha para exibir detalhes no cabeçalho
        $campanha = Campanha::findOrFail($campanha_id);

        // Busca o estudante logado (ajuste conforme a sua relação no Model User)
       $estudante = Auth::user()->estudante;
//dd("");
        // Lista os anteprojetos do estudante nesta campanha, do mais recente para o mais antigo
        $anteprojetos = Anteprojeto::where('campanha_id',$campanha->id)->where('estudante_id',$estudante->id)->orderBy('created_at','desc') ->paginate(10);
//dd($anteprojetos->first()->status);
            $hoje=Carbon::today();
            $prazoSubmissao=Carbon::parse($campanha->prazo_submissao);
            $prazoMelhorias=Carbon::parse($campanha->prazo_melhorias);
        
            $fase1=false;
                
                if($hoje->lt($prazoSubmissao)){
                    $fase1=true;
                }
            $fase2=false;
                if($campanha->prazo_melhorias!=null ){
                    if($hoje->lt($prazoMelhorias)){
                        $fase2=true;
                    }
                }

                  // 4. CÁLCULO DOS TOTAIS
    // Quantidade de Anteprojetos criados
    $totalAnteprojetos = $anteprojetos->count();

    // SOMA TOTAL DE VERSÕES: Percorre todos os anteprojetos e soma a contagem de versões de cada um
    $totalVersoes = $anteprojetos->sum(function($anteprojeto) {
        return $anteprojeto->versoes->count();
    });
   // dd($fase1,$fase2);
        return view('app.private.dashboard.estudantes.anteprojetos.index', compact('totalAnteprojetos','totalVersoes','campanha', 'anteprojetos','fase1','fase2'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }
    public function create($id)
    {
        try  {
        $campanha = Campanha::findOrFail($id);
               if($campanha->status!="aberta"){
                return redirect->back()->with('error','Esta campanha já não está aberta');
               } 
        // Busca o estudante logado (ajuste conforme a sua relação no Model User)
       $estudante = Auth::user()->estudante;

        // Pegamos apenas o último anteprojeto para validar o status
    $ultimoAnteprojeto = Anteprojeto::where('campanha_id', $campanha->id)
        ->where('estudante_id', $estudante->id)
        ->latest()
        ->first();

    $hoje = Carbon::today();
    $prazoSubmissao = Carbon::parse($campanha->prazo_submissao);
    $prazoMelhorias = $campanha->prazo_melhorias ? Carbon::parse($campanha->prazo_melhorias) : null;

    // CASO 1: Dentro do prazo de submissão E não tem nenhum anteprojeto
    $podeSubmeterPrimeiraVez = $hoje->lte($prazoSubmissao) && !$ultimoAnteprojeto;

    // CASO 2: Dentro do prazo de melhorias E o último foi rejeitado
    // Assumi que o status de rejeição no seu banco seja 'rejeitado'
    $podeEnviarMelhoria = $prazoMelhorias 
        && $hoje->lte($prazoMelhorias) 
        && $ultimoAnteprojeto 
        && $ultimoAnteprojeto->status === 'rejeitado';

  // Quantidade de Anteprojetos criados
    $totalAnteprojetos =  Anteprojeto::where('campanha_id', $campanha->id)
        ->where('estudante_id', $estudante->id)->count();
 $anteprojetos=Anteprojeto::where('campanha_id', $campanha->id)
        ->where('estudante_id', $estudante->id)->get();
    // SOMA TOTAL DE VERSÕES: Percorre todos os anteprojetos e soma a contagem de versões de cada um
    $totalVersoes = $anteprojetos->sum(function($anteprojeto) {
        return $anteprojeto->versoes->count();
    });
         
    if($ultimoAnteprojeto){

    
          if($ultimoAnteprojeto->status =='rejeitado' && $totalAnteprojetos==1  && $totalVersoes>1)
 
                     return redirect()->back()->with('error', 'Espere uma outra campanha..');
        else if($ultimoAnteprojeto->status =='rejeitado' && $totalAnteprojetos>1)
        return redirect()->back()->with('error', 'Espere uma outra campanha..');
                       
}

    // Validação Final: Se não for nenhum dos dois casos, bloqueia
    if (!$podeSubmeterPrimeiraVez && !$podeEnviarMelhoria) {
        return redirect()->back()->with('error', 'Você não tem permissão para realizar uma submissão no momento ou os prazos expiraram.');
    }
        return view('app.private.dashboard.estudantes.anteprojetos.create', compact('campanha'));
   } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }


    public function store(Request $request)
    {
        try {

            $request->validate([
                'campanha_id' => ['required','exists:campanhas,id'],
                'descricao' => ['required','string'],
                'arquivo' => ['required','file','mimes:pdf,doc,docx','max:256000'],
                'titulo' => 'required|string|max:255|min:3',

            ]);

            DB::beginTransaction();

            $user = auth()->user();
            $estudante = $user->estudante;

            // 🔴 VERIFICAR SE ESTÁ NA CAMPANHA
            $pertence = DB::table('campanha_estudante')
                ->where('campanha_id', $request->campanha_id)
                ->where('estudante_id', $estudante->id)
                ->exists();

            if(!$pertence){
                return back()->with('error','Você não pertence a esta campanha.');
            }

            // 🔴 VERIFICAR SE JÁ TEM SUBMISSÃO
         /*   $ultimo = Anteprojeto::where('campanha_id',$request->campanha_id)
                ->where('estudante_id',$estudante->id)
                ->latest()
                ->first();

            $versao = $ultimo ? $ultimo->versao + 1 : 1;
*/
            // UPLOAD
            $arquivo = $request->file('arquivo')->store('anteprojetos','public');

            $anteprojeto=Anteprojeto::create([
                'campanha_id' => $request->campanha_id,
                'estudante_id' => $estudante->id,
             
                'titulo' => $request->titulo,

                'status' => 'submetido'
                
            ]);

            AnteprojetoVersao::create([
                'anteprojeto_id' => $anteprojeto->id,
               'descricao' => $request->descricao,
            'arquivo' => $arquivo,
            'status' => 'submetido'
                ]);
            /*
              'anteprojeto_id',
        'arquivo',
        'descricao',
        'numero_versao',
        'status'
            */

            DB::commit();

            return redirect()->route('estudante.anteprojetos.meuAnteprojeto',$request->campanha_id)->with('success','Anteprojeto enviado com sucesso!');

       } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
}