<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerTransport extends Model
{
    const JENIS_KENDARAAN_MOBIL = 'mobil';

    const JENIS_KENDARAAN_MOTOR = 'motor';

    const JENIS_KENDARAAN_TRUK = 'truk';

    protected $fillable = [
        'customer_id',
        'nama',
        'jenis_kendaraan',
        'merk',
        'no_polisi',
        'sn',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
