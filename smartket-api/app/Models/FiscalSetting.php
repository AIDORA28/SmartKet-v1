<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class FiscalSetting extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'fiscal_settings';

    protected $fillable = [
        'ruc',
        'legal_name',
        'default_receipt_type',
        'boleta_simple_enabled',
    ];

    protected $casts = [
        'ruc' => 'encrypted',
        'legal_name' => 'encrypted',
        'boleta_simple_enabled' => 'boolean',
    ];
}

