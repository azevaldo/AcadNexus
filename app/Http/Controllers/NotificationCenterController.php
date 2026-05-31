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
        try{
        $user = Auth::user();
        $notificacoes = $user->notifications()->paginate(10);
        
        return view('app.private.dashboard.notifications.index', compact('notificacoes'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }

    /**
     * Interceptador de cliques: Marca como lida e redireciona para o painel alvo.
     */
    public function redirectAndRead($id, Request $request)
    {
        try{
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Marca como lida no clique
        $notification->markAsRead();

        $target = $request->query('target');
        $targetId = $request->query('target_id');

        // Mapeamento dinâmico de para onde redirecionar baseado no escopo do perfil
        switch ($target) {
            case 'anteprojeto':
                // Redireciona o Professor para ver o anteprojeto submetido
                return redirect()->route('anteprojetosAdmin.historico', $targetId);
            case 'anteprojeto_resultado':
                // Redireciona o Estudante para ver o resultado do seu anteprojeto
                return redirect()->route('estudantes.anteprojetos.index');
            case 'acompanhamento_fase':
                // Redireciona o Professor para avaliar a fase submetida
                return redirect()->route('tutor.acompanhamento.show', $targetId);
            case 'estudante_fase_resultado':
                // Redireciona o Estudante para ver a correção do Tutor
                return redirect()->route('estudantes.acompanhamento.fases.index');
            default:
                return redirect()->route('dashboard');
        }
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Alternar estado entre Lida / Não Lida manualmente por botão
     */
    public function toggleRead($id)
    {
        try{
        $notification = Auth::user()->notifications()->findOrFail($id);

        if ($notification->read()) {
            $notification->update(['read_at' => null]);
            return redirect()->back()->with('success', 'Notificação marcada como não lida.');
        } else {
            $notification->markAsRead();
            return redirect()->back()->with('success', 'Notificação marcada como lida.');
        }
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Marcar tudo o que está pendente como lido de uma só vez
     */
    public function markAllAsRead()
    {try{
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }
}