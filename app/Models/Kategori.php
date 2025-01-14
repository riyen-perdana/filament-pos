<?php

namespace App\Models;

use App\Enums\IsAktif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['kategori_nama', 'kategori_slug', 'is_aktif'];
    
    protected $casts = ['is_aktif' => IsAktif::class];

    protected function kategoriNama(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }

    public function asset() : HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
