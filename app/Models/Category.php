<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//Clase donde sea crea el modelo de entidad de la tabla categories
class Category extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'categories'; // Nombre de la tabla asociada a este modelo

    protected $fillable = 
    [
        'name',
        'created_at',
        'updated_at',
    ];
}
