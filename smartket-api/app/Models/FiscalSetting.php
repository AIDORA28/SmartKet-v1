<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalSetting extends Model
{
    use HasFactory;

    protected $table = 'fiscal_settings';

    protected $fillable = [
        'ruc',
        'razon_social',
        'comprobante_default',
        'boleta_simple_enabled',
    ];

    protected $casts = [
        'ruc' => 'encrypted',
        'razon_social' => 'encrypted',
        'boleta_simple_enabled' => 'boolean',
    ];
}

