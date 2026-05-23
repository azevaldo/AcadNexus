<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\SubmissaoFase;

class NovaSubmissaoFaseNotification extends Notification
{
    use Queueable;

    protected $submissao;

    public function __construct(SubmissaoFase $submissao)
    {
        $this->submissao = $submissao;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $fase = $this->submissao->fase;
        $acompanhamento = $fase->acompanhamento;
        $estudanteUser = $acompanhamento->estudante->user;
        
        $url = route('tutor.acompanhamento.show', ['id' => $acompanhamento->id]);

        return (new MailMessage)
            ->subject('📂 Nova Submissão de Fase de TCC')
            ->greeting("Olá, Prof. {$notifiable->name},")
            ->line("O seu orientando **{$estudanteUser->name}** realizou uma nova submissão de arquivo na **{$fase->fase}**.")
            ->action('Avaliar Submissão', $url)
            ->line('Aceda ao sistema para fazer o download do arquivo enviado e atribuir o respetivo feedback.')
            ->salutation('Boas orientações, Sistema de TCC.');
    }

    public function toArray($notifiable)
    {
        $fase = $this->submissao->fase;
        $acompanhamento = $fase->acompanhamento;
        $estudanteUser = $acompanhamento->estudante->user;

        return [
            'titulo' => 'Nova Submissão de Fase de TCC',
            'mensagem' => "O seu orientando {$estudanteUser->name} realizou uma submissão de arquivo na {$fase->fase}.",
            'url' => route('tutor.acompanhamento.show', ['id' => $acompanhamento->id, 'target' => 'acompanhamento_fase', 'target_id' => $fase->id]),
            'tipo' => 'acompanhamento_submissao'
        ];
    }
}