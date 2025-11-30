<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTransaction extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';

    const STATUS_INPROGRESS = 'in_progress';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'kode',
        'customer_id',
        'transport_id',
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

    public function transport(): BelongsTo
    {
        return $this->belongsTo(CustomerTransport::class, 'transport_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(SalesTransactionDetail::class);
    }
}
