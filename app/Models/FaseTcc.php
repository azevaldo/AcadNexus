<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaseTcc extends Model
{
    protected $table = 'fases_tcc';

    protected $fillable = [
        'acompanhamento_id','fase','inicio','fim','descricao','status'
    ];

    public function acompanhamento()
    {
        return $this->belongsTo(AcompanhamentoTcc::class);
    }

   public function submissao()
    {
        // Se na sua tabela 'submissao_fase' a coluna for 'fase_tcc_id':
        return $this->hasOne(SubmissaoFase::class, 'fase_tcc_id'); 
        
        // Se a coluna na tabela for apenas 'fase_id', use:
        // return $this->hasOne(SubmissaoFase::class, 'fase_id');
    }

   public function avaliacao()
    {
        return $this->hasOneThrough(
            AvaliacaoFase::class, 
            SubmissaoFase::class,
            'acompanhamento_id', // Chave estrangeira em submissao_fase
            'submissao_fase_id', // Chave estrangeira em avaliacao_fases
            'id',                // Chave local em fases_tcc
            'id'                 // Chave local em submissao_fase
        );
    }
}