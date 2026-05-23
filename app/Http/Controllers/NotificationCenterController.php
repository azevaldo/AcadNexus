<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationCenterController extends Controller
{
    /**
     * Central/Inbox de Notificações do Utilizador Logado
     */
    public function index()
    {
        $user = Auth::user();
        $notificacoes = $user->notifications()->paginate(15);
        
        return view('app.private.dashboard.notifications.index', compact('notificacoes'));
    }

    /**
     * Interceptador de cliques: Marca como lida e redireciona para o painel alvo.
     */
    public function redirectAndRead($id, Request $request)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Marca como lida no clique
        $notification->markAsRead();

        $target = $request->query('target');
        $targetId = $request->query('target_id');

        // Mapeamento dinâmico de para onde redirecionar baseado no escopo do perfil
        switch ($target) {
            case 'anteprojeto':
                // Redireciona o Professor para ver o anteprojeto submetido
                return redirect()->route('professores.anteprojetos.show', $targetId);
            case 'anteprojeto_resultado':
                // Redireciona o Estudante para ver o resultado do seu anteprojeto
                return redirect()->route('estudantes.anteprojetos.index');
            case 'acompanhamento_fase':
                // Redireciona o Professor para avaliar a fase submetida
                return redirect()->route('professores.acompanhamentos.fases.show', $targetId);
            case 'estudante_fase_resultado':
                // Redireciona o Estudante para ver a correção do Tutor
                return redirect()->route('estudantes.acompanhamento.fases.index');
            default:
                return redirect()->route('dashboard');
        }
    }

    /**
     * Alternar estado entre Lida / Não Lida manualmente por botão
     */
    public function toggleRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        if ($notification->read()) {
            $notification->update(['read_at' => null]);
            return redirect()->back()->with('success', 'Notificação marcada como não lida.');
        } else {
            $notification->markAsRead();
            return redirect()->back()->with('success', 'Notificação marcada como lida.');
        }
    }

    /**
     * Marcar tudo o que está pendente como lido de uma só vez
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }
}