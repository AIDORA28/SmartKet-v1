<?php

namespace App\Http\Controllers\Api\Compartido;

use App\Http\Controllers\Controller;
use App\Models\Compartido\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // El trait BelongsToTenant aplica el scope automáticamente
        return Product::all();
    }
}

