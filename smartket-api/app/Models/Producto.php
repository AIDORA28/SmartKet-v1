<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // La tabla del tenant es 'products'
    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        // agrega más columnas según tu migración
    ];
}

