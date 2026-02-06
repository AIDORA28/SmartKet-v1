<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Branch extends Model
{
    use HasFactory, UsesTenantConnection, BelongsToTenant;

    protected $fillable = [
        'name',
        'address',
        'is_active',
    ];
}
