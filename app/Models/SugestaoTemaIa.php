<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SugestaoTemaIa extends Model
{
    protected $table = 'sugestoes_tema_ia';

     
    protected $fillable = ['estudante_id', 'area_id', 'disciplinas', 'competencias', 'tipo_projeto', 'titulo', 'descricao'];

    protected $casts = [
        'disciplinas' => 'array',
        'competencias' => 'array',
    ];

    public function estudante() { return $this->belongsTo(Estudante::class); }
    public function area() { return $this->belongsTo(AreaCientifica::class); }
}