<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_detail';
    protected $fillable = [
        'transaksi_id',
        'asset_id',
        'jumlah',
        'harga'
    ];

    public function transaksi() : HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function asset() : BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
