<?php

namespace App\Http\Controllers;

use App\Models\Anteprojeto;
use App\Models\AnteprojetoVersao;
use App\Models\Campanha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Storage;

class AnteprojetoController extends Controller
{
    public function destroy_versao($id)
    {
        $versao = AnteprojetoVersao::findOrFail($id);
        
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

        return back()->with('success', 'Versão eliminada com sucesso!');
    }
// Exibe o formulário de NOVA VERSÃO (V2, V3...)
    public function novaVersao($id)
    {
        $anteprojeto = Anteprojeto::with('campanha')->findOrFail($id);
        
        // Bloqueio de segurança: Só permite se o status for 'pendente_melhorias'
      /*  if ($anteprojeto->status !== 'melhorias') {
            return redirect()->back()->with('error', 'Não é possível enviar uma nova versão para este projeto no momento.');
        }
Ç*/
        return view('app.private.dashboard.estudantes.anteprojetos.versao', compact('anteprojeto'));
    }

    public function updateTitulo(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255|min:5',
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
    }
    // Armazena a nova versão
    public function storeVersao(Request $request, $id)
    {
         try {
        $request->validate([
            'descricao' => 'required|string|min:20',
            'arquivo'   => 'required|mimes:pdf,docx|max:10240',
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
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar submissão.');
        }
    }
public function show($id)
{
    // Carrega o anteprojeto com a relação da campanha para o cabeçalho
    $anteprojeto = Anteprojeto::with('campanha')->findOrFail($id);

    // Opcional: Validar se o usuário logado tem permissão (ex: se é o dono ou coordenador)
    // if (Auth::user()->estudante->id !== $anteprojeto->estudante_id) { abort(403); }
//dd($anteprojeto->decisoes);
    return view('app.private.dashboard.estudantes.anteprojetos.show', compact('anteprojeto'));
}
public function meuAnteprojeto($campanha_id)
    {
        
        // Busca a campanha para exibir detalhes no cabeçalho
        $campanha = Campanha::findOrFail($campanha_id);

        // Busca o estudante logado (ajuste conforme a sua relação no Model User)
        //$estudante = Auth::user()->estudante;

        // Lista os anteprojetos do estudante nesta campanha, do mais recente para o mais antigo
        $anteprojetos = Anteprojeto::where('campanha_id', $campanha_id)
          //  ->where('estudante_id', $estudante->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('app.private.dashboard.estudantes.anteprojetos.index', compact('campanha', 'anteprojetos'));
    }
    public function create($id)
    {
        $campanha = Campanha::findOrFail($id);

        return view('app.private.dashboard.estudantes.anteprojetos.create', compact('campanha'));
    }


    public function store(Request $request)
    {
        try {

            $request->validate([
                'campanha_id' => ['required','exists:campanhas,id'],
                'descricao' => ['required','string'],
                'arquivo' => ['required','file','mimes:pdf,doc,docx','max:2048'],
                'titulo' => ['required'],

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

            return back()->with('success','Anteprojeto enviado com sucesso!');

        } catch (Exception $e) {

            DB::rollBack();
dd($e->getMessage());
            Log::error('Erro ao submeter anteprojeto: '.$e->getMessage());

            return back()->with('error','Erro ao submeter.');
        }
    }
}