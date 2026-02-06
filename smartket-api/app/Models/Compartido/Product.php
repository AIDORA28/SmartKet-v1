<?php

namespace App\Models\Compartido;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Product extends Model
{
    use HasFactory, UsesTenantConnection, BelongsToTenant;

    // La tabla del tenant es 'products'
    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        // agrega más columnas según tu migración
    ];
}

