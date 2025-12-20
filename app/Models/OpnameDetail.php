<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpnameDetail extends Model
{
    protected $fillable = [
        'opname_id',
        'product_id',
        'harga_pokok',
        'jumlah_awal',
        'jumlah_opname',
        'selisih',
        'total_selisih',
    ];

    public function opname(): BelongsTo
    {
        return $this->belongsTo(Opname::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
