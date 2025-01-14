<?php

namespace App\Models;

use App\Enums\IsAktif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $table = 'unit';
    protected $fillable = ['unit_nama', 'unit_slug','is_aktif'];

    protected $casts = ['is_aktif' => IsAktif::class];

    /**
     * TODO: Accessor and Mutator Field unit_nama
     * @return Attribute
     */
    public function unitNama(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }

    /**
     * TODO: Relation HasMany User
     * @return HasMany
     */
    public function user() : HasMany
    {
        return $this->hasMany(User::class);
    }

    public function asset() : HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
