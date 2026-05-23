<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AvaliacaoFase;

class AvaliacaoFaseNotification extends Notification
{
    use Queueable;

    protected $avaliacao;

    public function __construct(AvaliacaoFase $avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $submissao = $this->avaliacao->submissao;
        $fase = $submissao->fase;
        $acompanhamento = $fase->acompanhamento;
        $tutorUser = $this->avaliacao->tutor->user;
        
        $url = route('estudante.acompanhamento.show', ['acompanhamento' => $acompanhamento->id]);

        return (new MailMessage)
            ->subject('⭐ Fase de TCC Avaliada pelo Tutor')
            ->greeting("Olá, {$notifiable->name},")
            ->line("O Professor {$tutorUser->name} avaliou a sua submissão da **{$fase->fase}**.")
            ->line("**Resultado:** " . strtoupper($this->avaliacao->decisao))
            ->action('Ver Avaliação no Painel', $url)
            ->line('Consulte o seu painel de acompanhamento para ler as observações completas deixadas pelo seu orientador.')
            ->salutation('Atenciosamente, Portal de TCC.');
    }

    public function toArray($notifiable)
    {
        $submissao = $this->avaliacao->submissao;
        $fase = $submissao->fase;
        $acompanhamento = $fase->acompanhamento;
        $tutorUser = $this->avaliacao->tutor->user;

        return [
            'titulo' => 'Fase de TCC Avaliada pelo Tutor',
            'mensagem' => "O Professor {$tutorUser->name} avaliou a sua submissão da {$fase->fase}. Resultado: " . strtoupper($this->avaliacao->decisao) . ".",
            'url' => route('estudante.acompanhamento.show', ['acompanhamento' => $acompanhamento->id, 'target' => 'estudante_fase_resultado', 'target_id' => $fase->id]),
            'tipo' => 'acompanhamento_avaliacao'
        ];
    }
}