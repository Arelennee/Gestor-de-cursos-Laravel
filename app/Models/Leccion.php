<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'curso_id',
        'titulo',
        'contenido',
        'video_url',
        'orden',
    ];

    public function curso(){
        return $this->belongsTo(Curso::class);
    }
}
