<?php

namespace App\Observers;

use App\Models\AnteprojetoVersao;
use App\Notifications\NovoAnteprojetoNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class AnteprojetoVersaoObserver
{
    public function created(AnteprojetoVersao $anteprojetoVersao)
    {
        // 1. Encontra a campanha e o departamento do curso vinculado
        $campanha = $anteprojetoVersao->anteprojeto->campanha;
        $departamentoId = $campanha->curso->departamento_id;

        // 2. Busca os Users de todos os professores que pertencem a esse departamento
        $usuariosProfessores = User::whereHas('professor.departamentos', function ($query) use ($departamentoId) {
            $query->where('departamentos.id', $departamentoId);
        })->get();

        // 3. Dispara a notificação para todos os professores encontrados
        if ($usuariosProfessores->isNotEmpty()) {
            Notification::send($usuariosProfessores, new NovoAnteprojetoNotification($anteprojetoVersao));
        }
    }
}