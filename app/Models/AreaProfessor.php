<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaProfessor extends Model
{
    protected $table = 'areas_professor';

    protected $fillable = [
        'professor_id','area_cientifica_id'
    ];
}