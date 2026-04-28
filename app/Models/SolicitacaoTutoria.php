<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoTutoria extends Model
{
    protected $table = 'solicitacoes_tutoria';

    protected $fillable = [
        'estudante_id','professor_id',
        'tipo','origem','status'
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}