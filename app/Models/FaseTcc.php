<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaseTcc extends Model
{
    protected $table = 'fases_tcc';

    protected $fillable = [
        'acompanhamento_id', 
        'fase', 
        'mes_inicio', // Alterado
        'mes_fim',    // Alterado
        'descricao', 
        'status'
    ];

    public function acompanhamento()
    {
        return $this->belongsTo(AcompanhamentoTcc::class);
    }

    public function submissao()
    {
        return $this->hasOne(SubmissaoFase::class, 'fase_tcc_id');
    }

    // Acessores para facilitar a exibição das datas reais baseadas no mês de referência
    // Acessores para facilitar a exibição das datas reais
public function getDataInicioRealAttribute()
{
    // Verifica se o acompanhamento e a data existem antes de calcular
    if (!$this->acompanhamento || !$this->acompanhamento->mes_referencia) {
        return null;
    }

    // Se não usou o $casts no outro model, use o Carbon::parse() aqui
    return \Carbon\Carbon::parse($this->acompanhamento->mes_referencia)
        ->addMonths($this->mes_inicio - 1);
}

public function getDataFimRealAttribute()
{
    if (!$this->acompanhamento || !$this->acompanhamento->mes_referencia) {
        return null;
    }

    return \Carbon\Carbon::parse($this->acompanhamento->mes_referencia)
        ->addMonths($this->mes_fim - 1);
}
}