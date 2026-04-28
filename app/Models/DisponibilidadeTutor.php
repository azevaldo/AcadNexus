<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisponibilidadeTutor extends Model
{
    protected $table = 'disponibilidade_tutor';

    protected $fillable = [
        'professor_id','max_orientacoes',
        'orientacoes_ativas','ativo'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}