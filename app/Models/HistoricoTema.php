<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoTema extends Model
{
    protected $table = 'historico_temas';

    protected $fillable = [
        'estudante_id','tema','acao','observacao'
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }
}