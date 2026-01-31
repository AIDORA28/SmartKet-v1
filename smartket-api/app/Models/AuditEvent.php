<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditEvent extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'audit_events';

    protected $fillable = [
        'user_id', 'tenant_id', 'event_type', 'route', 'message', 'meta',
    ];
}

