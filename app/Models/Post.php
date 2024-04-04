<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id'
    ];

    public function user()
    {
        // Hace relación a la tabla de User
        return $this->belongsTo(User::class)->select(['name', 'username']);
    }

    public function comentarios()
    {   
        // Un post puede tener múltiples comentarios 
        return $this->hasMany(Comentario::class);
    } 

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function checkLike(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

}
