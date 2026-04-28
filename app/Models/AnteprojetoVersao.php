<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnteprojetoVersao extends Model
{
    protected $table = 'anteprojeto_versoes';

    protected $fillable = [
        'anteprojeto_id',
        'arquivo',
        'descricao',
        'numero_versao',
        'status' // Status da versão (enviado, aprovado, rejeitado, melhorias)
    ];

    public function anteprojeto()
    {
        return $this->belongsTo(Anteprojeto::class);
    }

    // Cada versão pode ter uma decisão/parecer do coordenador
    public function decisao()
    {
        return $this->hasOne(DecisaoAnteprojeto::class, 'anteprojeto_versao_id');
    }
    
  protected static function booted()
    {
        static::creating(function ($versao) {
            // Busca o maior número de versão para este anteprojeto específico
            $ultima = self::where('anteprojeto_id', $versao->anteprojeto_id)->max('numero_versao');
            
            // Define o número da versão atual
            $versao->numero_versao = $ultima ? $ultima + 1 : 1;
        });
    }
}