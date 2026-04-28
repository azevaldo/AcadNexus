<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoTrocaTema extends Model
{
    protected $table = 'solicitacoes_troca_tema';

    protected $fillable = [
        'estudante_id','tema_atual',
        'tema_proposto','justificativa',
        'documento','status','diretor_id'
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function diretor()
    {
        return $this->belongsTo(User::class, 'diretor_id');
    }
}