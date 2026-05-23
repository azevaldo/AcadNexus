<?php

namespace App\Observers;

use App\Models\SubmissaoFase;
use App\Notifications\NovaSubmissaoFaseNotification;

class SubmissaoFaseObserver
{
    public function created(SubmissaoFase $submissaoFase)
    {
        // Encontra o tutor principal atribuído à relação do estudante
        $acompanhamento = $submissaoFase->fase->acompanhamento;
        $estudante = $acompanhamento->estudante;
        
        // Verifica a tabela pivô estudante_tutores através do relacionamento definido
        $tutorRelacao = $estudante->tutorRelacao; 

        if ($tutorRelacao && $tutorRelacao->tutor) {
            $tutorUser = $tutorRelacao->tutor->user;
            if ($tutorUser) {
                $tutorUser->notify(new NovaSubmissaoFaseNotification($submissaoFase));
            }
        }
    }
}