<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professores';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departamentos()
    {
        return $this->belongsToMany(Departamento::class, 'departamento_professores');
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function areas()
    {
        return $this->belongsToMany(
            AreaCientifica::class,
            'areas_professor',
            'professor_id',
            'area_cientifica_id'
        );
    }
}