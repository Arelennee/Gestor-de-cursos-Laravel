<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'inscripciones';
    protected $fillable = [
        'user_id',
        'curso_id',
        'fecha_inscripcion',
    ];

    /**
     * Get the user that owns the inscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course that the inscription belongs to.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}

