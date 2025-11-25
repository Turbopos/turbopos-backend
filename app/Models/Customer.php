<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'whatsapp',
        'keterangan',
    ];

    public function transports(): HasMany
    {
        return $this->hasMany(CustomerTransport::class);
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class);
    }
}
