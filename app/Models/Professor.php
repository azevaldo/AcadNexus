<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professores';

    protected $fillable = ['user_id','max_orientacoes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departamentos()
    {
        return $this->belongsToMany(Departamento::class, 'departamento_professores');
    }

       public function acompanhamentos()
    {
        return $this->hasMany(AcompanhamentoTcc::class,'tutor_id');
    }
     // 🔥 estudantes como tutor principal
    public function estudantes()
    {
        return $this->hasMany(EstudanteTutor::class, 'tutor_id');
    }

    // 🔥 estudantes como co-tutor
    public function coorientados()
    {
        return $this->hasMany(EstudanteTutor::class, 'co_tutor_id');
    }

    public function areas()
    {
        return $this->belongsToMany(
            AreaCientifica::class,
            'areas_professor',
            'professor_id',
            'area_cientifica_id'
        );
    }
    // Dentro do Model Professor.php
public function orientacoes()
{
    return $this->hasMany(EstudanteTutor::class, 'tutor_id');
}
}