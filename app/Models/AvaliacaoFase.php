<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoFase extends Model
{
    protected $table = 'avaliacao_fases';

    protected $fillable = [
        'submissao_fase_id','tutor_id','decisao','descricao'
    ];

    public function submissao()
    {
        return $this->belongsTo(SubmissaoFase::class, 'submissao_fase_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Professor::class);
    }
}