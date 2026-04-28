<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anteprojeto extends Model
{
    protected $table = 'anteprojetos';

    protected $fillable = [
        'campanha_id', 
        'estudante_id', 
        'titulo', // Adicionado para identificar o tema
        'status'  // Status global (em_analise, aprovado, rejeitado)
    ];

    public function campanha()
    {
        return $this->belongsTo(Campanha::class);
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }
// App\Models\Anteprojeto.php

public function decisoes()
{
    // Certifique-se de que o nome do Model da tabela de avaliações é este
    return $this->hasMany(DecisaoAnteprojeto::class, 'anteprojeto_id');
}
    // Relação com todas as versões enviadas
    public function versoes()
    {
        return $this->hasMany(AnteprojetoVersao::class, 'anteprojeto_id');
    }

    // Atalho para pegar a versão mais recente
    public function ultimaVersao()
    {
        return $this->hasOne(AnteprojetoVersao::class, 'anteprojeto_id')->latestOfMany();
    }
}