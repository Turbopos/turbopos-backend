<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTransactionDetail extends Model
{
    protected $fillable = [
        'sales_transaction_id',
        'product_id',
        'harga',
        'jumlah',
        'subtotal',
    ];

    public function salesTransaction(): BelongsTo
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
