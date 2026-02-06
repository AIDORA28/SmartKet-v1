<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_monthly',
        'is_trial',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'is_trial' => 'boolean',
    ];
}
