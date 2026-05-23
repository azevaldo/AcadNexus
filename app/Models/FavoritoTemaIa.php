<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritoTemaIa extends Model
{
    protected $table = 'favoritos_tema_ia';

    protected $fillable = ['estudante_id','sugestao_id'];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }


    public function sugestao() { return $this->belongsTo(SugestaoTemaIa::class, 'sugestao_id'); }
}