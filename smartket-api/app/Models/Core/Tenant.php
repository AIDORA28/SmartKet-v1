<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends BaseTenant
{
    use HasFactory;
    
    // Permitimos asignación masiva de nuestros campos personalizados
    protected $fillable = [
        'business_name',
        'slug',
        'business_type',
        'logo_path',
        'db_name',
        'db_user',
        'db_password',
        'setup_complete',
    ];

    protected $casts = [
        'db_name' => 'encrypted',
        'db_user' => 'encrypted',
        'db_password' => 'encrypted',
        'setup_complete' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_tenant')->withPivot('quantity');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
