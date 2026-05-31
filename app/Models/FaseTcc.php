<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaseTcc extends Model
{
    protected $table = 'fases_tcc';

    protected $fillable = [
        'acompanhamento_id', 'fase', 'mes_inicio', 'mes_fim', 'descricao', 'status'
    ];

    public function acompanhamento()
    {
        return $this->belongsTo(AcompanhamentoTcc::class);
    }

    // 🌟 ALTERADO: Uma fase agora possui VÁRIAS submissões (Histórico)
    public function submissoes()
    {
        return $this->hasMany(SubmissaoFase::class, 'fase_tcc_id')->latest();
    }

    // 💡 Auxiliar para obter rapidamente a última tentativa de envio
    public function ultimaSubmissao()
    {
        return $this->hasOne(SubmissaoFase::class, 'fase_tcc_id')->latestOfMany();
    }

    public function getDataInicioRealAttribute()
    {
        if (!$this->acompanhamento || !$this->acompanhamento->mes_referencia) return null;
        return \Carbon\Carbon::parse($this->acompanhamento->mes_referencia)->addMonths($this->mes_inicio - 1);
    }

    public function getDataFimRealAttribute()
    {
        if (!$this->acompanhamento || !$this->acompanhamento->mes_referencia) return null;
        return \Carbon\Carbon::parse($this->acompanhamento->mes_referencia)->addMonths($this->mes_fim - 1);
    }
}