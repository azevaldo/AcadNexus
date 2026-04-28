<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcompanhamentoTcc extends Model
{
    protected $table = 'acompanhamento_tcc';

    protected $fillable = [
        'estudante_id','tutor_id','co_tutor_id',
        'anteprojeto_id','status'
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function coTutor()
    {
        return $this->belongsTo(Tutor::class, 'co_tutor_id');
    }

    public function fases()
    {
        return $this->hasMany(FaseTcc::class, 'acompanhamento_id');
    }
}
