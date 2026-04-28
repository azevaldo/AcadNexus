<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissaoFase extends Model
{
    protected $table = 'submissao_fase';

    protected $fillable = [
        'fase_tcc_id','descricao_estudante','arquivo'
    ];

public function fase()
    {
        return $this->belongsTo(FaseTcc::class, 'fase_tcc_id');
    }

    public function avaliacao()
    {
        return $this->hasOne(AvaliacaoFase::class, 'submissao_fase_id');
    }
}