<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoTutor extends Model
{
    protected $table = 'avaliacoes_tutor';

    protected $fillable = [
        'professor_id','estudante_id','nota','comentario'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }
}