<?php

namespace App\Http\Controllers;

use App\Models\Estudante;
use App\Models\Tutor;
use App\Models\EstudanteTutor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstudanteTutorController extends Controller
{
    public function vincularTutores(Request $request, $estudante_id)
{
    try {
        // 1. Validação básica de campos (Garante IDs válidos e impede Tutor == Co-Tutor)
        $request->validate([
            'tutor_id' => 'required|exists:professores,id',
            'co_tutor_id' => 'nullable|exists:professores,id|different:tutor_id',
        ], [
            'co_tutor_id.different' => 'O co-tutor não pode ser a mesma pessoa que o tutor principal.'
        ]);

        // 2. Validação de Limite de Vagas para o Tutor Principal
        $professor = \App\Models\Professor::findOrFail($request->tutor_id);
        
        // Conta quantas orientações ATIVAS este professor possui atualmente
        $orientacoesAtivas = \App\Models\EstudanteTutor::where('tutor_id', $professor->id)
            ->where('ativo', true)
            ->count();

        // Verifica se o estudante já possui um vínculo com este professor
        $jaEhOTutorAtual = \App\Models\EstudanteTutor::where('estudante_id', $estudante_id)
            ->where('tutor_id', $professor->id)
            ->exists();

        // Se estourou o limite e NÃO é o tutor atual dele, bloqueia a requisição
        if ($orientacoesAtivas >= $professor->max_orientacoes && !$jaEhOTutorAtual) {
            return back()->with('error', "O professor {$professor->user->name} já atingiu o limite máximo de {$professor->max_orientacoes} orientações.")
                         ->withInput();
        }

        // 3. Salva ou atualiza o registro na tabela pivô
        EstudanteTutor::updateOrCreate(
            ['estudante_id' => $estudante_id],
            [
                'tutor_id' => $request->tutor_id,
                'co_tutor_id' => $request->co_tutor_id,
                'ativo' => true // Garante que a relação nasça ativa
            ]
        );

        return back()->with('success', 'Orientadores atribuídos com sucesso!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Deixa os erros de validação padrão do Laravel passarem direto para a View
        throw $e;
    } catch (\Exception $e) {
        // Trata erros inesperados do banco ou sistema de forma limpa
        \Log::error('Erro ao vincular tutores: ' . $e->getMessage());
        
        return back()->with('error', 'Ocorreu um erro ao processar a atribuição. Tente novamente.')
                     ->withInput();
    }
}

     public function desvincular(Request $request, $estudante_id)
{
    try {
        // 1. Localiza a relação de tutoria atual
        $relacao = EstudanteTutor::where('estudante_id', $estudante_id)->firstOrFail();
        $tipo = $request->input('tipo'); // 'tutor' ou 'co_tutor'

        // 2. Validação baseada no tipo de desvinculação
        if ($tipo === 'tutor') {
            // Verifica se o Tutor Principal possui algum acompanhamento com o estudante
            $existeAcompanhamento = \App\Models\AcompanhamentoTcc::where('estudante_id', $estudante_id)
                ->where('tutor_id', $relacao->tutor_id)
                ->exists();

            if ($existeAcompanhamento) {
                return back()->with('error', 'Não é possível remover o Tutor Principal pois já existe um acompanhamento de TCC ativo associado a esta orientação.');
            }

            // Se passou na validação, remove toda a relação (não há orientação sem tutor principal)
            $relacao->delete();
            $mensagem = "Orientadores removidos com sucesso!";

        } else if ($tipo === 'co_tutor') {
            // Garante que existe um co-tutor antes de tentar verificar
            if (!$relacao->co_tutor_id) {
                return back()->with('error', 'Este estudante não possui um co-tutor vinculado.');
            }

            // Verifica se o Co-Tutor possui algum acompanhamento com o estudante
            $existeAcompanhamentoCoTutor = \App\Models\AcompanhamentoTcc::where('estudante_id', $estudante_id)
                ->where('co_tutor_id', $relacao->co_tutor_id)
                ->exists();

            if ($existeAcompanhamentoCoTutor) {
                return back()->with('error', 'Não é possível remover o Co-Tutor pois ele faz parte de um acompanhamento de TCC em andamento para este estudante.');
            }

            // Se passou na validação, desvincula apenas o co-tutor deixando o campo nulo
            $relacao->update(['co_tutor_id' => null]);
            $mensagem = "Co-tutor removido com sucesso!";
            
        } else {
            return back()->with('error', 'Tipo de desvinculação inválido.');
        }

        return back()->with('success', $mensagem);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return back()->with('error', 'Vínculo de tutoria não encontrado para este estudante.');
    } catch (\Exception $e) {
        \Log::error('Erro ao desvincular orientador: ' . $e->getMessage());
        
        return back()->with('error', 'Ocorreu um erro interno ao processar a desvinculação. Tente novamente.')
                     ->withInput();
    }
}
}