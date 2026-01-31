<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Devuelve la lista de productos del tenant actual.
     */
    public function index(Request $request)
    {
        // Spatie multitenancy debe haber inicializado la conexión del tenant
        $productos = Producto::all();
        return response()->json($productos);
    }
}

