<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'balance',
        'user_id',
    ];
}
