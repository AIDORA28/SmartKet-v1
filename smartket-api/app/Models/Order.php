<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Order extends Model
{
    use HasFactory, UsesTenantConnection, BelongsToTenant;

    protected $fillable = [
        'branch_id',
        'status',
        'mesa_id',
        'staff_id',
        'tenant_id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
