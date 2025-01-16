<?php

namespace App\Models;

use App\Enums\IsAktif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $table = 'asset';
    protected $fillable = [
        'asset_kode',
        'asset_nama',
        'asset_deskripsi',
        'kategori_id',
        'unit_id',
        'asset_harga',
        'asset_stok',
        'jenis_asset',
        'is_share',
        'is_aktif'
    ];

    protected $casts = [
        'is_aktif' => IsAktif::class
    ];

    public function assetNama() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }

    public function kategori() : BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function transaksiDetail() : HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
