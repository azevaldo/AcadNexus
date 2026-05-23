<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = ['nome'];

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'departamento_professores');
    }

    public function coordenador()
    {
        return $this->hasOne(Coordenador::class)->where('ativo', true);
    }
    public function estudantes()
{
    return $this->hasManyThrough(Estudante::class, Curso::class);
}
}