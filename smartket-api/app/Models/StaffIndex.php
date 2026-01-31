<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class StaffIndex extends Model
{
    use HasFactory;

    protected $table = 'staff_index';
    protected $guarded = [];
}
