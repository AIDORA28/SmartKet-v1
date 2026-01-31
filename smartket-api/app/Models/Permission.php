<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\BelongsToTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Permission extends Model
{
    use HasFactory, UsesTenantConnection, BelongsToTenant;

    protected $fillable = [
        'name',
        'display_name',
        'group_name',
        'tenant_id',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
