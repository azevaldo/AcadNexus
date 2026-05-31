<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoTutoria;
use App\Models\EstudanteTutor;
use App\Models\Tutor;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SolicitacoesTutoriaController extends Controller
{
    /**
     * LADO DO ESTUDANTE
     */

    // Lista o histórico de solicitações do aluno
    public function indexEstudante()
    {
        try{
        $estudante = auth()->user()->estudante;
        $solicitacoes = SolicitacaoTutoria::where('estudante_id', $estudante->id)
            ->with('professor.user')
            ->latest()
            ->paginate(10);

        return view('app.private.dashboard.estudantes.solicitacoes.historico', compact('solicitacoes'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    // Tela para escolher um professor e solicitar tutoria
public function create()
{
    try{
    // 1. Obtém o usuário logado e seus dados de estudante
    $user = auth()->user();
    
    // Carrega o estudante com o curso para saber qual é o departamento
    $estudante = \App\Models\Estudante::with('curso')->where('user_id', $user->id)->firstOrFail();

    // Captura o ID do departamento do curso do estudante
    // (Ajuste o nome do campo 'departamento_id' se na sua tabela de cursos for diferente)
    $departamentoId = $estudante->curso->departamento_id;

    // 2. Busca apenas os professores do MESMO departamento
   // 2. Busca apenas os professores do MESMO departamento
$tutores = Professor::with(['user'])
    // Filtra os professores que pertencem ao departamento na tabela dinâmica
    ->whereHas('departamentos', function ($query) use ($departamentoId) {
        $query->where('departamentos.id', $departamentoId);
    }) 
    // Conta as orientações ativas baseadas no relacionamento correto do seu Model
    ->withCount(['estudantes as orientacoes_ativas_count' => function ($query) {
        // Filtra as orientações que estão atualmente ativas na tabela estudante_tutores
        $query->where('ativo', true);
    }])
    ->get();

    // Retorna a view brutalista com a lista de professores devidamente filtrada
    return view('app.private.dashboard.estudantes.solicitacoes.create', compact('tutores'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    // Processa o envio da solicitação
    public function store(Request $request, $professor_id)
    {
        try{
        $estudante = auth()->user()->estudante;

        // 1. Validar se já possui tutor principal
        if ($request->tipo === 'tutor') {
            $jaTemTutor = EstudanteTutor::where('estudante_id', $estudante->id)
                ->whereNotNull('tutor_id')
                ->exists();
            if ($jaTemTutor) {
                
                return back()->with('error', 'Você já possui um tutor principal vinculado.');
            }
        }

         if ($request->tipo === 'co_tutor') {
            $jaTemCoTutor = EstudanteTutor::where('estudante_id', $estudante->id)
                ->whereNotNull('co_tutor_id')
                ->exists();
            if ($jaTemCoTutor) {
                
                return back()->with('error', 'Você já possui um Co-Tutor vinculado.');
            }

             $jaTemTutor2 = EstudanteTutor::where('estudante_id', $estudante->id)
                ->whereNotNull('tutor_id')
                ->exists();
    if(!$jaTemTutor2){
                return back()->with('error', 'Não é possivel solicitar co-tutor sem ter tutor.');
    }
        }



        // 3. Evitar duplicidade de pedido pendente
        $pendente = SolicitacaoTutoria::where('estudante_id', $estudante->id)
            ->where('professor_id', $professor_id)
            ->where('status', 'pendente')
            ->exists();

        if ($pendente) {
            return back()->with('error', 'Já existe uma solicitação pendente para este professor.');
        }

        SolicitacaoTutoria::create([
            'estudante_id' => $estudante->id,
            'professor_id' => $professor_id,
            'tipo' => $request->tipo,
            'origem' => 'manual',
            'status' => 'pendente'
        ]);

        return redirect()->route('solicitacoes.estudante.index')
            ->with('success', 'Solicitação enviada com sucesso!');
            } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * LADO DO PROFESSOR (TUTOR)
     */

    // Lista as solicitações que o professor recebeu
    public function indexTutor()
    {
        $professor = auth()->user()->professor;
        $solicitacoes = SolicitacaoTutoria::where('professor_id', $professor->id)
            ->with('estudante.user')
            ->latest()
            ->paginate(10);

        return view('app.private.dashboard.othes.professores.solicitacoes.historico', compact('solicitacoes'));
    }

    // Aceitar ou Recusar
    public function responder(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:aceite,recusado']);
        $solicitacao = SolicitacaoTutoria::findOrFail($id);

        DB::beginTransaction();
        try {
            $solicitacao->update(['status' => $request->status]);

            if ($request->status === 'aceite') {
                $tutor = Professor::where('id', $solicitacao->professor_id)->firstOrFail();

                // Vincula automaticamente na tabela de relação
                if ($solicitacao->tipo === 'tutor') {
                    EstudanteTutor::updateOrCreate(
                        ['estudante_id' => $solicitacao->estudante_id],
                        ['tutor_id' => $tutor->id]
                    );
                } else {
                    EstudanteTutor::updateOrCreate(
                        ['estudante_id' => $solicitacao->estudante_id],
                        ['co_tutor_id' => $tutor->id]
                    );
                }
            }

            DB::commit();
            return back()->with('success', 'Resposta enviada e sistema atualizado!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro técnico: ' . $e->getMessage());
        }
    }
}    