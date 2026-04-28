<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaCientifica extends Model
{
    protected $table = 'areas_cientificas';

    protected $fillable = ['nome','descricao'];

    public function professores()
    {
        return $this->belongsToMany(
            Professor::class,
            'areas_professor',
            'area_cientifica_id',
            'professor_id'
        );
    }
}