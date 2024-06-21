<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

//Clase donde se instancia el modelo de entidad de la tabla products
class Products extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'products'; // Nombre de la tabla asociada a este modelo

    protected $fillable = 
    [
        'name',
        'description',
        'quantity',
        'category_id',
        'created_at',
        'updated_at',
    ];
}
