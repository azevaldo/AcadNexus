<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMelhoria extends Model
{
    use HasFactory;

    protected $table = 'itens_melhoria';

    protected $fillable = [
        'titulo',
        'descricao',
        
    ];

     
  public function decisoes()
{
    return $this->belongsToMany(DecisaoAnteprojeto::class, 'item_melhoria_decisao');
}
}