<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutores';

    protected $fillable = ['professor_id'];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function acompanhamentos()
    {
        return $this->hasMany(AcompanhamentoTcc::class);
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
}