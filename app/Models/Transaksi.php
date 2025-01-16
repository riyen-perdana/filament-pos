<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['konsumen_id', 'transaksi_tanggal', 'transaksi_harga', 'transaksi_status'];

    public function konsumen() : BelongsTo
    {
        return $this->belongsTo(Konsumen::class, 'konsumen_id');
    }
}
