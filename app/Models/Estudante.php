<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudante extends Model
{
    protected $table = 'estudantes';

    protected $fillable = ['user_id','curso_id','numero_matricula','regime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function campanhas()
{
    // O segundo argumento é o nome da tabela pivô que você criou na migration
    return $this->belongsToMany(Campanha::class, 'campanha_estudante');
}

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function anteprojetos()
    {
        return $this->hasMany(Anteprojeto::class);
    }

    public function acompanhamento()
    {
        return $this->hasOne(AcompanhamentoTcc::class);
    }
     // 🔥 RELAÇÃO COM PIVOT
    public function orientacoes()
    {
        return $this->hasMany(EstudanteTutor::class);
    }
    // No Model Estudante.php
public function tutorRelacao()
{
    // Relação 1 para 1 com a tabela pivô estudante_tutores
    return $this->hasOne(EstudanteTutor::class, 'estudante_id');
}
}