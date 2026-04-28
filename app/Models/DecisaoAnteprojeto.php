<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DecisaoAnteprojeto extends Model
{
    protected $table = 'decisoes_anteprojeto';

    protected $fillable = [
        'anteprojeto_versao_id', // Alterado: conecta à versão específica
        'coordenador_id',
        'decisao',
        'mensagem'
    ];

    public function versao()
    {
        return $this->belongsTo(AnteprojetoVersao::class, 'anteprojeto_versao_id');
    }

    public function coordenador()
    {
        return $this->belongsTo(Coordenador::class);
    }
}