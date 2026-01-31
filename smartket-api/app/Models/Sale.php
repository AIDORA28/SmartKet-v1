<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Sale extends Model
{
    use HasFactory, UsesTenantConnection, BelongsToTenant;

    protected $fillable = [
        'branch_id',
        'order_id',
        'total',
        'payment_method',
        'tenant_id',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
