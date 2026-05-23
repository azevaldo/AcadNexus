<?php

namespace App\Observers;

use App\Models\DecisaoAnteprojeto;
use App\Notifications\AvaliacaoAnteprojetoNotification;

class DecisaoAnteprojetoObserver
{
    public function created(DecisaoAnteprojeto $decisaoAnteprojeto)
    {
        // Obtém o User associado ao estudante dono daquele anteprojeto avaliado
        $estudanteUser = $decisaoAnteprojeto->versao->anteprojeto->estudante->user;

        if ($estudanteUser) {
            $estudanteUser->notify(new AvaliacaoAnteprojetoNotification($decisaoAnteprojeto));
        }
    }
}