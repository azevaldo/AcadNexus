<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'acesso',
        'telefone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
      public function professor()
    {
        return $this->hasOne(Professor::class);
    }

    public function estudante()
    {
        return $this->hasOne(Estudante::class);
    }
    // Dentro de App\Models\User.php

public function ehCoordenadorDoCurso($cursoId)
{
    // Busca o perfil do professor ligado a este usuário
    $professor = \App\Models\Professor::where('user_id', $this->id)->first();
    
    if (!$professor) {
        return false;
    }

    // Busca o curso para saber o departamento
    $curso = \App\Models\Curso::find($cursoId);
    
    if (!$curso) {
        return false;
    }

    // Checa se é o coordenador ativo do departamento do curso
    return \App\Models\Coordenador::where('professor_id', $professor->id)
        ->where('departamento_id', $curso->departamento_id)
        ->where('ativo', true)
        ->exists();
}

public function ehCoordenadorDoDepartamento($depaId)
{
    // Busca o perfil do professor ligado a este usuário
    $professor = \App\Models\Professor::where('user_id', $this->id)->first();
    
    if (!$professor) {
        return false;
    }

    // Busca o curso para saber o departamento
    $departamento = \App\Models\Departamento::find($depaId);
    
    if (!$departamento) {
        return false;
    }

    // Checa se é o coordenador ativo do departamento do curso
    return \App\Models\Coordenador::where('professor_id', $professor->id)
        ->where('departamento_id', $departamento->id)
        ->where('ativo', true)
        ->exists();
}

public function routeNotificationForTwilio()
    {
        return $this->telefone; // Ou o nome da coluna de telefone no seu banco
    }

}
