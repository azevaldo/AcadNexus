<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcompanhamentoTcc extends Model
{
    protected $table = 'acompanhamento_tcc';

    protected $fillable = [
        'estudante_id','tutor_id','co_tutor_id',
        'anteprojeto_id','status','mes_referencia'
    ];
protected $casts = [
    'mes_referencia' => 'date', // ou 'datetime'
];
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function coTutor()
    {
        return $this->belongsTo(Professor::class, 'co_tutor_id');
    }

    public function fases()
    {
        return $this->hasMany(FaseTcc::class, 'acompanhamento_id');
    }
}
