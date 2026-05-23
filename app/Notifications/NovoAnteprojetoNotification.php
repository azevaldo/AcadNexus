<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AnteprojetoVersao;

class NovoAnteprojetoNotification extends Notification
{
    use Queueable;

    protected $versao;

    public function __construct(AnteprojetoVersao $versao)
    {
        $this->versao = $versao;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
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