<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'description',
        'old_data',
        'new_data',
        'user_id',
    ];
}
