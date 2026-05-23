<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\DecisaoAnteprojeto;

class AvaliacaoAnteprojetoNotification extends Notification
{
    use Queueable;

    protected $decisao;

    public function __construct(DecisaoAnteprojeto $decisao)
    {
        $this->decisao = $decisao;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $versao = $this->decisao->versao;
        $anteprojeto = $versao->anteprojeto;
        
        // Utiliza a rota real que definiste para o e-mail
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