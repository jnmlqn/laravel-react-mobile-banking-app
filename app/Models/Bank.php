<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'provider',
        'bank',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
