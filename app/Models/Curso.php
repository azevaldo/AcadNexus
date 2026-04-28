<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = ['nome','departamento_id'];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function estudantes()
    {
        return $this->hasMany(Estudante::class);
    }

    public function campanhas()
    {
        return $this->hasMany(Campanha::class);
    }
}
