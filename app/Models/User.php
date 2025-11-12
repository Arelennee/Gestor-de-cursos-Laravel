<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const ROL_ADMIN = 'admin';
    const ROL_PROFESOR = 'profesor';
    const ROL_ESTUDIANTE = 'estudiante';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function esAdmin()
    {
        return $this->rol === self::ROL_ADMIN;
    }

    public function esProfesor()
    {
        return $this->rol === self::ROL_PROFESOR;
    }

    public function esEstudiante()
    {
        return $this->rol === self::ROL_ESTUDIANTE;
    }
    
    public function cursos(){
        return $this->hasMany(Curso::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function cursosInscritos(){
        return $this->belongsToMany(Curso::class, 'inscripciones');
    }
    public function comentarios(){
        return $this->hasMany(Comentario::class);
    }
}
