<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel; // <-- ADICIONADO
use NotificationChannels\Twilio\TwilioSmsMessage; // <-- ADICIONADO
use App\Models\AnteprojetoVersao;

class NovoAnteprojetoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $versao;
    public $tries = 3;

    public function __construct(AnteprojetoVersao $versao)
    {
        $this->versao = $versao;
    }

    public function via($notifiable)
    {
        // Adicionado o TwilioChannel à lista
        return ['database', 'mail', TwilioChannel::class];
    }

    public function toMail($notifiable)
    {
        $anteprojeto = $this->versao->anteprojeto;
        $estudanteUser = $anteprojeto->estudante->user;
        
        $url = route('anteprojetosAdmin.historico', ['id' => $anteprojeto->id]);

        return (new MailMessage)
            ->subject('📌 Novo Anteprojeto/Versão Submetida')
            ->greeting("Olá, Prof. {$notifiable->name},")
            ->line("O estudante **{$estudanteUser->name}** submeteu a Versão nº {$this->versao->numero_versao} do anteprojeto titulado: \"{$anteprojeto->titulo}\".")
            ->action('Analisar Proposta', $url)
            ->line('Este projeto pertence ao departamento do seu curso. Aceda para conferir o histórico completo e os dados enviados.')
            ->salutation('Atenciosamente, Direção de TCC.');
    }

    /**
     * Envio de SMS via Twilio
     */
    public function toTwilio($notifiable)
    {
        $anteprojeto = $this->versao->anteprojeto;
        $estudanteUser = $anteprojeto->estudante->user;

        return (new TwilioSmsMessage())
            ->content("Ola Prof. {$notifiable->name}, o estudante {$estudanteUser->name} submeteu a Versao {$this->versao->numero_versao} do anteprojeto '{$anteprojeto->titulo}'.");
    }

    public function toArray($notifiable)
    {
        $anteprojeto = $this->versao->anteprojeto;
        $estudanteUser = $anteprojeto->estudante->user;

        return [
            'titulo' => 'Novo Anteprojeto/Versão Submetida',
            'mensagem' => "O estudante {$estudanteUser->name} submeteu a Versão nº {$this->versao->numero_versao} do anteprojeto intitulado: \"{$anteprojeto->titulo}\".",
            'url' => route('anteprojetosAdmin.historico', ['id' => $anteprojeto->id, 'target' => 'anteprojeto', 'target_id' => $anteprojeto->id]),
            'tipo' => 'anteprojeto_submissao'
        ];
    }
}