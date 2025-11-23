<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesTransaction extends Model
{
    const STATUS_PENDING = 'pending';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'kode',
        'customer_id',
        'user_id',
        'ppn',
        'subtotal',
        'diskon',
        'total',
        'status',
        'transaction_at',
    ];

    protected $casts = [
        'transaction_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesTransactionDetails(): HasMany
    {
        return $this->hasMany(SalesTransactionDetail::class);
    }
}
