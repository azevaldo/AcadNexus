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
    // App\Models\Curso.php

public function anteprojetos()
{
    // Um curso tem muitos anteprojetos ATRAVÉS das campanhas
    return $this->hasManyThrough(
        Anteprojeto::class, 
        Campanha::class,
        'curso_id',     // Chave estrangeira em campanhas
        'campanha_id',  // Chave estrangeira em anteprojetos
        'id',           // Chave local em cursos
        'id'            // Chave local em campanhas
    );
}
}
