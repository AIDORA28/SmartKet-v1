<?php

namespace App\Models\Compartido;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Category extends Model
{
    use HasFactory, UsesTenantConnection, BelongsToTenant;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'tenant_id',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
