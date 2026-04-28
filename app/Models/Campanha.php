<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campanha extends Model
{
    protected $table = 'campanhas';

    protected $fillable = [
        'curso_id','coordenador_id','titulo','descricao',
        'prazo_submissao','prazo_melhorias','prazo_novo','status'
    ];

    
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function coordenador()
    {
        return $this->belongsTo(Coordenador::class);
    }

    public function estudantes()
    {
        return $this->belongsToMany(Estudante::class, 'campanha_estudante');
    }

    public function anteprojetos()
    {
        return $this->hasMany(Anteprojeto::class);
    }
}