<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distributor extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'whatsapp',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
