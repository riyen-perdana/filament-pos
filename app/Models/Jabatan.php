<?php

namespace App\Models;

use App\Enums\IsAktif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $fillable = [
        'jabatan_nama',
        'jabatan_slug',
        'is_aktif'
    ];
    protected $casts = [
        'is_aktif' => IsAktif::class
    ];

    protected function jabatanNama(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }

    public function user() : HasMany
    {
        return $this->hasMany(User::class);
    }
}
