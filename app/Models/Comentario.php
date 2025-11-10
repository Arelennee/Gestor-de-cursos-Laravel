<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    public function curso(){
        return $this->belongsTo(Curso::class);
    }
    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
