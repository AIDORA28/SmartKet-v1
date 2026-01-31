<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Multitenancy\Models\Tenant;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            $currentTenantId = app(\Spatie\Multitenancy\Models\Tenant::class)::current()?->id;
            if (empty($model->tenant_id) && $currentTenantId) {
                $model->tenant_id = $currentTenantId;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            $currentTenantId = app(\Spatie\Multitenancy\Models\Tenant::class)::current()?->id;
            if ($currentTenantId) {
                $table = $builder->getModel()->getTable();
                $builder->where($table . '.tenant_id', $currentTenantId);
            }
        });
    }
}
