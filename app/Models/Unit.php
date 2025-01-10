<?php

namespace App\Models;

use App\Enums\IsAktif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Unit extends Model
{
    protected $table = 'unit';
    protected $fillable = ['unit_nama', 'unit_slug','is_aktif'];

    protected $casts = ['is_aktif' => IsAktif::class];

    public function unitNama(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }
}
