<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudanteTutor extends Model
{
    protected $table = 'estudante_tutores';

    protected $fillable = [
        'estudante_id',
        'tutor_id',
        'co_tutor_id'
    ];

    // 🔥 estudante
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    // 🔥 tutor principal
    public function tutor()
    {
        return $this->belongsTo(Professor::class, 'tutor_id');
    }

    // 🔥 co-tutor
    public function coTutor()
    {
        return $this->belongsTo(Professor::class, 'co_tutor_id');
    }
}