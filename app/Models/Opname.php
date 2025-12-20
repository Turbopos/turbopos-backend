<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opname extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'user_id',
        'total',
        'transaction_at',
    ];

    protected $casts = [
        'transaction_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OpnameDetail::class);
    }
}
