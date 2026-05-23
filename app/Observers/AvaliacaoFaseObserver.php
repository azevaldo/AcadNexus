<?php

namespace App\Observers;

use App\Models\AvaliacaoFase;
use App\Notifications\AvaliacaoFaseNotification;

class AvaliacaoFaseObserver
{
    public function created(AvaliacaoFase $avaliacaoFase)
    {
        $estudanteUser = $avaliacaoFase->submissao->fase->acompanhamento->estudante->user;

        if ($estudanteUser) {
            $estudanteUser->notify(new AvaliacaoFaseNotification($avaliacaoFase));
        }
    }
}