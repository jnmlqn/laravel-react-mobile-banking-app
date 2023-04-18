<?php

namespace App\Models;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'mode',
        'bank_id',
        'email',
        'amount',
        'last_current_balance',
        'description',
        'user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
