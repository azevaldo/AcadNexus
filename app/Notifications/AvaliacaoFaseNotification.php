<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel; // <-- ADICIONADO
use NotificationChannels\Twilio\TwilioSmsMessage; // <-- ADICIONADO
use App\Models\AvaliacaoFase;

class AvaliacaoFaseNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    public $tries = 3;
    protected $avaliacao;

    public function __construct(AvaliacaoFase $avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    public function via($notifiable)
    {
        // Adicionado o TwilioChannel à lista
        return ['database', 'mail', TwilioChannel::class];
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

    /**
     * Envio de SMS via Twilio
     */
    public function toTwilio($notifiable)
    {
        $submissao = $this->avaliacao->submissao;
        $fase = $submissao->fase;
        $tutorUser = $this->avaliacao->tutor->user;
        $resultado = strtoupper($this->avaliacao->decisao);

        return (new TwilioSmsMessage())
            ->content("Ola {$notifiable->name}, sua submissao na fase '{$fase->fase}' foi avaliada pelo Prof. {$tutorUser->name}. Resultado: {$resultado}. Verifique no painel.");
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