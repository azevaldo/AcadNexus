<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordenador extends Model
{
    protected $table = 'coordenadores';

    protected $fillable = ['departamento_id','professor_id','ativo'];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}