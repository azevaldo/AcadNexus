<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel; // <-- ADICIONADO
use NotificationChannels\Twilio\TwilioSmsMessage; // <-- ADICIONADO
use App\Models\SubmissaoFase;

class NovaSubmissaoFaseNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    public $tries = 3;
    protected $submissao;

    public function __construct(SubmissaoFase $submissao)
    {
        $this->submissao = $submissao;
    }

    public function via($notifiable)
    {
        // Adicionado o TwilioChannel à lista
        return ['database', 'mail', TwilioChannel::class];
    }

    public function toMail($notifiable)
    {
        $fase = $this->submissao->fase;
        $acompanhamento = $fase->acompanhamento;
        $estudanteUser = $acompanhamento->estudante->user;
        
        $url = route('tutor.acompanhamento.show', ['acompanhamento' => $acompanhamento]);

        return (new MailMessage)
            ->subject('📂 Nova Submissão de Fase de TCC')
            ->greeting("Olá, Prof. {$notifiable->name},")
            ->line("O seu orientando **{$estudanteUser->name}** realizou uma nova submissão de arquivo na **{$fase->fase}**.")
            ->action('Avaliar Submissão', $url)
            ->line('Aceda ao sistema para fazer o download do arquivo enviado e atribuir o respetivo feedback.')
            ->salutation('Boas orientações, Sistema de TCC.');
    }

    /**
     * Envio de SMS via Twilio
     */
    public function toTwilio($notifiable)
    {
        $fase = $this->submissao->fase;
        $estudanteUser = $fase->acompanhamento->estudante->user;

        return (new TwilioSmsMessage())
            ->content("Ola Prof. {$notifiable->name}, o seu orientando {$estudanteUser->name} realizou uma nova submissao de arquivo na fase '{$fase->fase}'.");
    }

    public function toArray($notifiable)
    {
        $fase = $this->submissao->fase;
        $acompanhamento = $fase->acompanhamento;
        $estudanteUser = $acompanhamento->estudante->user;

        return [
            'titulo' => 'Nova Submissão de Fase de TCC',
            'mensagem' => "O seu orientando {$estudanteUser->name} realizou uma submissão de arquivo na {$fase->fase}.",
            'url' => route('tutor.acompanhamento.show', ['acompanhamento' => $acompanhamento, 'target' => 'acompanhamento_fase', 'target_id' => $fase->id]),
            'tipo' => 'acompanhamento_submissao'
        ];
    }
}