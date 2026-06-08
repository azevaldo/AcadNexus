<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twilio\TwilioChannel; // <-- ADICIONADO
use NotificationChannels\Twilio\TwilioSmsMessage; // <-- ADICIONADO
use App\Models\DecisaoAnteprojeto;

class AvaliacaoAnteprojetoNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    public $tries = 3;
    protected $decisao;

    public function __construct(DecisaoAnteprojeto $decisao)
    {
        $this->decisao = $decisao;
    }

    public function via($notifiable)
    {
        // Adicionado o TwilioChannel à lista
        return ['database', 'mail', TwilioChannel::class];
    }

    public function toMail($notifiable)
    {
        $versao = $this->decisao->versao;
        $anteprojeto = $versao->anteprojeto;
        $url = route('estudante.anteprojetos.show', ['id' => $anteprojeto->id]);

        return (new MailMessage)
            ->subject('📢 Parecer do Anteprojeto Emitido')
            ->greeting("Olá, {$notifiable->name},")
            ->line("A Versão nº {$versao->numero_versao} do seu anteprojeto \"{$anteprojeto->titulo}\" recebeu uma avaliação.")
            ->line("**Decisão:** " . strtoupper($this->decisao->decisao))
            ->action('Visualizar Parecer', $url)
            ->line('Aceda à plataforma para verificar os detalhes e os itens de melhoria solicitados.')
            ->salutation('Atenciosamente, Coordenação de TCC.');
    }

    /**
     * Envio de SMS via Twilio
     */
    public function toTwilio($notifiable)
    {
        $versao = $this->decisao->versao;
        $anteprojeto = $versao->anteprojeto;
        $decisaoTexto = strtoupper($this->decisao->decisao);

        return (new TwilioSmsMessage())
            ->content("Ola {$notifiable->name}, o parecer do seu anteprojeto '{$anteprojeto->titulo}' (v{$versao->numero_versao}) foi emitido. Decisao: {$decisaoTexto}. Acesse o portal.");
    }

    public function toArray($notifiable)
    {
        $versao = $this->decisao->versao;
        $anteprojeto = $versao->anteprojeto;

        return [
            'titulo' => 'Parecer do Anteprojeto Emitido',
            'mensagem' => "A Versão nº {$versao->numero_versao} do seu anteprojeto \"{$anteprojeto->titulo}\" recebeu uma decisão: " . strtoupper($this->decisao->decisao) . ".",
            'url' => route('estudante.anteprojetos.show', ['id' => $anteprojeto->id, 'target' => 'anteprojeto_resultado', 'target_id' => $anteprojeto->id]),
            'tipo' => 'anteprojeto_avaliacao'
        ];
    }
}