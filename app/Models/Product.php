<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const JENIS_BARANG = 'barang';

    const JENIS_JASA = 'jasa';

    protected $fillable = [
        'kode',
        'jenis',
        'category_id',
        'nama',
        'barcode',
        'distributor_id',
        'harga_pokok',
        'harga_jual',
        'stok',
        'satuan',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function purchaseOrderDetails(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function salesTransactionDetails(): HasMany
    {
        return $this->hasMany(SalesTransactionDetail::class);
    }
}
