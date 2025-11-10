<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'titulo',
        'descripcion',
        'categoria',
        'duracion',
        'nivel',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function lecciones(){
        return $this->hasMany(Leccion::class);
    }
    public function estudiantes(){
        return $this->belongsToMany(User::class, 'inscripciones');
    }
    public function comentarios(){
        return $this->hasMany(Comentario::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
